<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Queries\PaymentSessionQuery;

class SessionExpiryService
{
    public function __construct(private readonly ?PaymentSessionQuery $query = null)
    {
    }

    public function expireOverdue(?int $companyId = null): int
    {
        if ($companyId) {
            return ZeroPaySession::query()
                ->where('company_id', $companyId)
                ->whereIn('status', ['sent', 'awaiting_confirmation', 'pending_gateway'])
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->update(['status' => 'expired']);
        }

        return ZeroPaySession::query()
            ->whereIn('status', ['sent', 'awaiting_confirmation', 'pending_gateway'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);
    }
}
