<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachConversation;
use Modules\TitanReach\Models\ReachMessage;

class TwilioWhatsappService
{
    protected string $accountSid;
    protected string $authToken;
    protected string $fromNumber;
    protected string $baseUrl;

    public function __construct()
    {
        $this->accountSid = (string) config('titanreach.twilio.account_sid', '');
        $this->authToken  = (string) config('titanreach.twilio.auth_token', '');
        $this->fromNumber = (string) config('titanreach.twilio.from_whatsapp_number', '');
        $this->baseUrl    = rtrim((string) config('titanreach.twilio.base_url', 'https://api.twilio.com/2010-04-01/Accounts'), '/');
    }

    /**
     * Send a WhatsApp message via Twilio.
     *
     * @return array<string,mixed>
     */
    public function send(string $to, string $body, ?string $mediaUrl = null): array
    {
        $whatsappTo = str_starts_with($to, 'whatsapp:') ? $to : 'whatsapp:' . $to;
        $from       = str_starts_with($this->fromNumber, 'whatsapp:') ? $this->fromNumber : 'whatsapp:' . $this->fromNumber;

        $fields = [
            'To'   => $whatsappTo,
            'From' => $from,
            'Body' => $body,
        ];

        if ($mediaUrl !== null) {
            $fields['MediaUrl'] = $mediaUrl;
        }

        return $this->callApi('Messages.json', $fields);
    }

    /**
     * Handle an inbound WhatsApp webhook payload.
     *
     * @param  array<string,mixed>  $twilioPayload
     */
    public function receiveInbound(array $twilioPayload): ReachMessage
    {
        $from = $twilioPayload['From'] ?? '';
        $body = $twilioPayload['Body'] ?? '';
        $sid  = $twilioPayload['MessageSid'] ?? null;

        // Normalise the WhatsApp number (strip the "whatsapp:" prefix for storage).
        $phone = str_replace('whatsapp:', '', $from);

        $contact = ReachContact::firstOrCreate(
            ['whatsapp_number' => $from],
            ['name' => $phone, 'phone' => $phone, 'company_id' => null]
        );

        $conversation = ReachConversation::firstOrCreate(
            ['contact_id' => $contact->id, 'channel' => 'whatsapp', 'status' => 'open'],
            ['company_id' => $contact->company_id, 'external_id' => $sid]
        );

        $message = $conversation->messages()->create([
            'direction' => 'inbound',
            'content'   => $body,
            'channel'   => 'whatsapp',
            'sent_at'   => now(),
            'meta'      => ['twilio_sid' => $sid, 'raw' => $twilioPayload],
        ]);

        $conversation->update([
            'last_message' => substr($body, 0, 255),
            'unread_count' => $conversation->unread_count + 1,
        ]);

        return $message;
    }

    /**
     * @param  array<string,mixed>  $fields
     * @return array<string,mixed>
     */
    private function callApi(string $path, array $fields): array
    {
        $url = $this->baseUrl . '/' . $this->accountSid . '/' . ltrim($path, '/');

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
            throw new \RuntimeException('Twilio WhatsApp REST error: ' . $err);
        }

        $data = json_decode((string) $resp, true);
        if ($code >= 400) {
            throw new \RuntimeException('Twilio WhatsApp HTTP ' . $code . ': ' . (is_array($data) ? ($data['message'] ?? $resp) : $resp));
        }

        return is_array($data) ? $data : [];
    }
}
