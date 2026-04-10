<?php

namespace Modules\BookingModule\Services\Captcha;

interface CaptchaVerifier
{
    public function verify(?string $token, ?string $ip = null): bool;
}
