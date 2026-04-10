<?php

namespace Modules\TitanReach\Services;

use Modules\TitanReach\Models\ReachContact;
use Modules\TitanReach\Models\ReachConversation;
use Modules\TitanReach\Models\ReachMessage;

class TwilioVoiceService
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
     * Initiate an outbound call.
     *
     * @return array<string,mixed>
     */
    public function makeCall(string $to, string $twimlUrl, ?string $from = null): array
    {
        return $this->callApi('Calls.json', [
            'To'  => $to,
            'From' => $from ?? $this->fromNumber,
            'Url'  => $twimlUrl,
        ]);
    }

    /**
     * Hang up an active call.
     *
     * @return array<string,mixed>
     */
    public function hangupCall(string $callSid): array
    {
        return $this->callApi('Calls/' . $callSid . '.json', [
            'Status' => 'completed',
        ]);
    }

    /**
     * Generate TwiML XML for a spoken message.
     *
     * @param  array<string,mixed>  $options
     */
    public function generateTwiml(string $message, array $options = []): string
    {
        $voice  = $options['voice'] ?? 'alice';
        $action = isset($options['action']) ? ' action="' . htmlspecialchars($options['action'], ENT_XML1) . '"' : '';

        return '<?xml version="1.0" encoding="UTF-8"?><Response><Say voice="' . htmlspecialchars($voice, ENT_XML1) . '"' . $action . '>'
            . htmlspecialchars($message, ENT_XML1)
            . '</Say></Response>';
    }

    /**
     * Start recording an active call.
     *
     * @return array<string,mixed>
     */
    public function recordCall(string $callSid): array
    {
        return $this->callApi('Calls/' . $callSid . '/Recordings.json', []);
    }

    /**
     * Handle an inbound voice webhook and persist to the inbox.
     *
     * @param  array<string,mixed>  $twilioPayload
     */
    public function receiveInbound(array $twilioPayload): ReachMessage
    {
        $from   = $twilioPayload['From'] ?? '';
        $sid    = $twilioPayload['CallSid'] ?? null;
        $status = $twilioPayload['CallStatus'] ?? 'ringing';

        $contact = ReachContact::firstOrCreate(
            ['phone' => $from],
            ['name' => $from, 'company_id' => null]
        );

        $conversation = ReachConversation::firstOrCreate(
            ['contact_id' => $contact->id, 'channel' => 'call', 'external_id' => $sid],
            ['company_id' => $contact->company_id, 'status' => 'open']
        );

        $message = $conversation->messages()->create([
            'direction' => 'inbound',
            'content'   => 'Inbound call – status: ' . $status,
            'channel'   => 'call',
            'sent_at'   => now(),
            'meta'      => ['call_sid' => $sid, 'raw' => $twilioPayload],
        ]);

        $conversation->update(['last_message' => 'Inbound call']);

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
            throw new \RuntimeException('Twilio Voice REST error: ' . $err);
        }

        $data = json_decode((string) $resp, true);
        if ($code >= 400) {
            throw new \RuntimeException('Twilio Voice HTTP ' . $code . ': ' . (is_array($data) ? ($data['message'] ?? $resp) : $resp));
        }

        return is_array($data) ? $data : [];
    }
}
