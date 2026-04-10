<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachConversation;
use Modules\TitanReach\Models\ReachMessage;

class TwilioSmsService
{
    protected string $accountSid;
    protected string $authToken;
    protected string $fromNumber;
    protected string $baseUrl;

    public function __construct()
    {
        $this->accountSid = (string) config('titanreach.twilio.account_sid', '');
        $this->authToken  = (string) config('titanreach.twilio.auth_token', '');
        $this->fromNumber = (string) config('titanreach.twilio.from_sms_number', '');
        $this->baseUrl    = rtrim((string) config('titanreach.twilio.base_url', 'https://api.twilio.com/2010-04-01/Accounts'), '/');
    }

    /**
     * Send a single SMS message via Twilio.
     *
     * @return array<string,mixed>
     */
    public function send(string $to, string $body, ?string $from = null): array
    {
        return $this->callApi('Messages.json', [
            'To'   => $to,
            'From' => $from ?? $this->fromNumber,
            'Body' => $body,
        ]);
    }

    /**
     * Broadcast an SMS to multiple contacts.
     *
     * @param  array<ReachContact|array<string,mixed>>  $contacts
     * @return array<int,array<string,mixed>>
     */
    public function broadcast(array $contacts, string $body): array
    {
        $results = [];
        foreach ($contacts as $contact) {
            $phone = is_array($contact) ? ($contact['phone'] ?? null) : $contact->phone;
            if (empty($phone)) {
                continue;
            }
            try {
                $results[] = $this->send($phone, $body);
            } catch (\Throwable $e) {
                $results[] = ['error' => $e->getMessage(), 'to' => $phone];
            }
        }
        return $results;
    }

    /**
     * Handle an inbound SMS webhook payload and persist it to the inbox.
     *
     * @param  array<string,mixed>  $twilioPayload
     */
    public function receiveInbound(array $twilioPayload): ReachMessage
    {
        $from = $twilioPayload['From'] ?? '';
        $body = $twilioPayload['Body'] ?? '';
        $sid  = $twilioPayload['MessageSid'] ?? null;

        $contact = ReachContact::firstOrCreate(
            ['phone' => $from],
            ['name' => $from, 'company_id' => null]
        );

        $conversation = ReachConversation::firstOrCreate(
            ['contact_id' => $contact->id, 'channel' => 'sms', 'status' => 'open'],
            ['company_id' => $contact->company_id, 'external_id' => $sid]
        );

        $message = $conversation->messages()->create([
            'direction' => 'inbound',
            'content'   => $body,
            'channel'   => 'sms',
            'sent_at'   => now(),
            'meta'      => ['twilio_sid' => $sid, 'raw' => $twilioPayload],
        ]);

        $conversation->update([
            'last_message' => substr($body, 0, 255),
            'unread_count' => $conversation->unread_count + 1,
        ]);

        return $message;
    }

    private function buildApiUrl(string $path): string
    {
        return $this->baseUrl . '/' . $this->accountSid . '/' . ltrim($path, '/');
    }

    /**
     * @param  array<string,mixed>  $fields
     * @return array<string,mixed>
     */
    private function callApi(string $path, array $fields): array
    {
        $url = $this->buildApiUrl($path);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->accountSid . ':' . $this->authToken);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $resp = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($resp === false) {
            throw new \RuntimeException('Twilio SMS REST error: ' . $err);
        }

        $data = json_decode((string) $resp, true);
        if ($code >= 400) {
            throw new \RuntimeException('Twilio SMS HTTP ' . $code . ': ' . (is_array($data) ? ($data['message'] ?? $resp) : $resp));
        }

        return is_array($data) ? $data : [];
    }
}
