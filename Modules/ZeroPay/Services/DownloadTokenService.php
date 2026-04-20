<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Actions\CreateDownloadTokenAction;
use Modules\ZeroPay\Models\ZeroPayDownloadToken;
use Modules\ZeroPay\Models\ZeroPaySession;

class DownloadTokenService
{
    public function __construct(private readonly ?CreateDownloadTokenAction $createAction = null)
    {
    }

    public function create(ZeroPaySession $session, string $type): ZeroPayDownloadToken
    {
        $action = $this->createAction ?? app(CreateDownloadTokenAction::class);

        return $action->execute($session, $type);
    }

    public function validateToken(string $token, string $type): ZeroPayDownloadToken
    {
        $downloadToken = ZeroPayDownloadToken::query()
            ->where('token', $token)
            ->where('type', $type)
            ->firstOrFail();

        if ($downloadToken->expires_at && now()->greaterThan($downloadToken->expires_at)) {
            abort(410, 'Download token expired.');
        }

        return $downloadToken;
    }
}
