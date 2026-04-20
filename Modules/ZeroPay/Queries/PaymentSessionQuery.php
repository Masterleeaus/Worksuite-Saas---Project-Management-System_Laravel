<?php

namespace Modules\ZeroPay\Queries;

use Modules\ZeroPay\Models\ZeroPaySession;

class PaymentSessionQuery
{
    public function forCompany(int $companyId)
    {
        return ZeroPaySession::query()->where('company_id', $companyId);
    }

    public function overdue(int $companyId)
    {
        return $this->forCompany($companyId)
            ->whereIn('status', ['sent', 'awaiting_confirmation', 'pending_gateway'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }
}
