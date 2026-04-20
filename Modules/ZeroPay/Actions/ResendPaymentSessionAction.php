<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\FollowupOrchestratorService;

class ResendPaymentSessionAction
{
    public function __construct(private readonly FollowupOrchestratorService $followupService)
    {
    }

    public function execute(ZeroPaySession $session)
    {
        return $this->followupService->queueEmail($session, 'ZeroPay session resend requested by operator.');
    }
}
