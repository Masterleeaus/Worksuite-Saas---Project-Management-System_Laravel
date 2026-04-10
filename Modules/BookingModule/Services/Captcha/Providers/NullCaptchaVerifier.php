<?php

namespace Modules\BookingModule\Services\Captcha\Providers;

use Modules\BookingModule\Services\Captcha\CaptchaVerifier;

class NullCaptchaVerifier implements CaptchaVerifier
{
    public function verify(?string $token, ?string $ip = null): bool
    {
        return true;
    }
}
