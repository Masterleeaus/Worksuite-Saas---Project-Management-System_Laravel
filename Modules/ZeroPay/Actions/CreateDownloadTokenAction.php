<?php

namespace Modules\ZeroPay\Actions;

use Illuminate\Support\Str;
use Modules\ZeroPay\Models\ZeroPayDownloadToken;
use Modules\ZeroPay\Models\ZeroPaySession;

class CreateDownloadTokenAction
{
    public function execute(ZeroPaySession $session, string $type, ?\DateTimeInterface $expiresAt = null): ZeroPayDownloadToken
    {
        return ZeroPayDownloadToken::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'invoice_id' => $session->invoice_id,
            'type' => $type,
            'token' => Str::lower(Str::random(64)),
            'expires_at' => $expiresAt ?? now()->addMinutes((int) config('zeropay.download_expiry_minutes', 1440)),
        ]);
    }
}
