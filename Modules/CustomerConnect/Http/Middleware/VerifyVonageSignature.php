<?php

namespace Modules\CustomerConnect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyVonageSignature
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('customerconnect.webhooks.vonage.validate_signatures', false)) {
            return $next($request);
        }

        $envKey = (string) config('customerconnect.webhooks.vonage.signature_secret_env', 'VONAGE_SIGNATURE_SECRET');
        $secret = config('services.vonage.signature_secret') ?: env($envKey);
        if (!$secret) {
            Log::warning('[CustomerConnect] Vonage signature verification skipped (missing secret).');
            return $next($request);
        }

        $sig = $request->input('sig') ?? $request->header('X-Nexmo-Signature');
        if (!$sig) {
            return response('Missing Vonage signature', 403);
        }

        // Simple scheme: build query string without sig, md5(secret + params)
        $params = $request->all();
        unset($params['sig']);
        ksort($params);
        $base = '';
        foreach ($params as $k => $v) {
            $base .= $k . '=' . $v . '&';
        }
        $base = rtrim($base, '&');
        $expected = md5($base . $secret);

        if (!hash_equals($expected, $sig)) {
            Log::warning('[CustomerConnect] Vonage signature mismatch', [
                'provided' => $sig,
                'expected' => $expected,
            ]);
            return response('Invalid Vonage signature', 403);
        }

        return $next($request);
    }
}
