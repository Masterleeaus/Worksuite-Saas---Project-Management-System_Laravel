<?php

namespace Modules\ZeroPay\Workflows;

use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\PaymentSessionService;
use Modules\ZeroPay\Services\SessionExpiryService;

class SessionLifecycleWorkflow
{
    public function __construct(
        private readonly PaymentSessionService $paymentSessionService,
        private readonly SessionExpiryService $sessionExpiryService,
    ) {
    }

    public function create(array $payload): ZeroPaySession
    {
        return $this->paymentSessionService->create($payload);
    }

    public function resend(ZeroPaySession $session)
    {
        return $this->paymentSessionService->resend($session);
    }

    public function expireOverdueSessions(?int $companyId = null): int
    {
        return $this->sessionExpiryService->expireOverdue($companyId);
    }
}
