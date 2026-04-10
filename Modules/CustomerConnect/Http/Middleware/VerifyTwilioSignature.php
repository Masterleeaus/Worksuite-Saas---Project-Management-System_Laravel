<?php

namespace Modules\CustomerConnect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyTwilioSignature
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('customerconnect.webhooks.twilio.validate_signatures', true)) {
            return $next($request);
        }

        $tokenEnv = (string) config('customerconnect.webhooks.twilio.auth_token_env', 'TWILIO_AUTH_TOKEN');
        $token = config('services.twilio.auth_token') ?: env($tokenEnv);
        if (!$token) {
            // Fail-open to avoid breaking inbound if token isn't configured, but log loudly.
            Log::warning('[CustomerConnect] Twilio signature verification skipped (missing token).', ['env' => $tokenEnv]);
            return $next($request);
        }

        $signature = $request->header('X-Twilio-Signature');
        if (!$signature) {
            return response('Missing Twilio signature', 403);
        }

        // Twilio signs the full URL + POST params (x-www-form-urlencoded).
        $url = $request->fullUrl();
        $params = $request->all();

        $expected = base64_encode(hash_hmac('sha1', $url . $this->sortedParamsString($params), $token, true));

        if (!hash_equals($expected, trim($signature))) {
            Log::warning('[CustomerConnect] Twilio signature mismatch', [
                'url' => $url,
                'provided' => $signature,
                'expected' => $expected,
            ]);
            return response('Invalid Twilio signature', 403);
        }

        return $next($request);
    }

    private function sortedParamsString(array $params): string
    {
        // Twilio requires params sorted by key, concatenated key+value (no separators).
        ksort($params);
        $out = '';
        foreach ($params as $k => $v) {
            if (is_array($v)) {
                // Flatten arrays deterministically
                $v = json_encode($v);
            }
            $out .= $k . $v;
        }
        return $out;
    }
}
