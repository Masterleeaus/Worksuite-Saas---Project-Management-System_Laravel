<?php

namespace Modules\BookingModule\Services;

use Illuminate\Http\Request;

class PublicCaptchaVerifier
{
    /**
     * Hook-only verifier.
     * If captcha is enabled, you should implement provider verification here.
     * Supported providers (hook): recaptcha, hcaptcha.
     */
    public function verify(Request $request): bool
    {
        $cfg = config('bookingmodule.public.captcha', ['enabled' => false, 'provider' => 'none']);
        if (!($cfg['enabled'] ?? false)) {
            return true;
        }

        $provider = $cfg['provider'] ?? 'none';
        $token = $request->input('captcha_token');

        if (!$token) {
            return false;
        }

        // Hook: implement real provider validation later.
        // For now, if enabled and provider is 'none', fail closed.
        if ($provider === 'none') {
            return false;
        }

        // Placeholder pass-through for future integration.
        return true;
    }
}
