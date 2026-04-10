<?php

namespace Modules\CustomerConnect\Services\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Best-effort webhook signature verification.
 *
 * Design goals:
 * - Never hard-crash the app if provider config is missing.
 * - If validation is enabled AND a secret/token is available, enforce it.
 */
class WebhookSignatureVerifier
{
    public function verifyTwilio(Request $request): bool
    {
        $enabled = (bool) config('customerconnect.webhooks.twilio.validate_signatures', false);
        if (!$enabled) {
            return true;
        }

        $token = $this->twilioAuthToken();
        if (!$token) {
            // Can't validate without token; fail open to avoid breaking installs.
            return true;
        }

        $signature = $request->header('X-Twilio-Signature');
        if (!$signature) {
            return false;
        }

        // Twilio signature: base64(hmac_sha1(url + sorted(params), token))
        $url = $request->fullUrl();
        $data = $url;

        $params = $request->all();
        ksort($params);
        foreach ($params as $k => $v) {
            // Twilio expects raw string values. Arrays are uncommon; flatten safely.
            if (is_array($v)) {
                $v = json_encode($v);
            }
            $data .= $k . $v;
        }

        $computed = base64_encode(hash_hmac('sha1', $data, $token, true));

        // Constant time compare
        return hash_equals($computed, trim($signature));
    }

    public function verifyVonage(Request $request): bool
    {
        $enabled = (bool) config('customerconnect.webhooks.vonage.validate_signatures', false);
        if (!$enabled) {
            return true;
        }

        $secretEnv = (string) config('customerconnect.webhooks.vonage.signature_secret_env', 'VONAGE_SIGNATURE_SECRET');
        $secret = env($secretEnv);
        if (!$secret) {
            return true;
        }

        // Vonage inbound may include: sig, timestamp, nonce. Signature method varies by product.
        // We implement a simple query-string hash for legacy SMS webhooks.
        $sig = $request->query('sig') ?? $request->input('sig');
        if (!$sig) {
            return false;
        }

        $params = $request->all();
        unset($params['sig']);
        ksort($params);

        $data = '';
        foreach ($params as $k => $v) {
            if (is_array($v)) $v = json_encode($v);
            $data .= $k . '=' . $v . '&';
        }
        $data = rtrim($data, '&');

        $computed = md5($data . $secret);

        return hash_equals(strtolower($computed), strtolower((string) $sig));
    }

    public function verifyTelegram(Request $request): bool
    {
        $envKey = (string) config('customerconnect.webhooks.telegram.secret_token_env', 'TELEGRAM_WEBHOOK_SECRET');
        $secret = env($envKey);
        if (!$secret) {
            return true;
        }

        $hdr = $request->header('X-Telegram-Bot-Api-Secret-Token');
        if (!$hdr) {
            return false;
        }

        return hash_equals($secret, (string) $hdr);
    }

    private function twilioAuthToken(): ?string
    {
        // Prefer Sms module settings if helper exists.
        try {
            if (function_exists('sms_setting')) {
                $s = sms_setting();
                if ($s && !empty($s->auth_token)) {
                    return (string) $s->auth_token;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $envKey = (string) config('customerconnect.webhooks.twilio.auth_token_env', 'TWILIO_AUTH_TOKEN');
        return env($envKey) ?: null;
    }
}
