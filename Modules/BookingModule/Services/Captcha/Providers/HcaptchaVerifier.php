<?php

namespace Modules\BookingModule\Services\Captcha\Providers;

use Illuminate\Support\Facades\Http;
use Modules\BookingModule\Services\Captcha\CaptchaVerifier;

class HcaptchaVerifier implements CaptchaVerifier
{
    public function __construct(private string $secret) {}

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (!$token) return false;

        $resp = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => $this->secret,
            'response' => $token,
            'remoteip' => $ip,
        ]);

        if (!$resp->ok()) return false;
        $json = $resp->json();

        return (bool)($json['success'] ?? false);
    }
}
