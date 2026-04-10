<?php

namespace Modules\BookingModule\Services\Captcha;

use Modules\BookingModule\Services\Captcha\Providers\HcaptchaVerifier;
use Modules\BookingModule\Services\Captcha\Providers\NullCaptchaVerifier;
use Modules\BookingModule\Services\Captcha\Providers\RecaptchaVerifier;

class CaptchaFactory
{
    public static function make(string $provider, ?string $secret): CaptchaVerifier
    {
        $provider = strtolower(trim($provider));

        return match ($provider) {
            'recaptcha' => $secret ? new RecaptchaVerifier($secret) : new NullCaptchaVerifier(),
            'hcaptcha'  => $secret ? new HcaptchaVerifier($secret)  : new NullCaptchaVerifier(),
            default     => new NullCaptchaVerifier(),
        };
    }
}
