<?php

namespace Modules\ZeroPay\Automation;

use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Services\FollowupOrchestratorService;

class RecoveryFollowupAutomation
{
    public function __construct(private readonly FollowupOrchestratorService $followupService)
    {
    }

    public function runForSession(ZeroPaySession $session): void
    {
        if (in_array($session->status, ['paid', 'failed', 'expired'], true)) {
            return;
        }

        $this->followupService->queueEmail($session, 'Payment reminder from ZeroPay recovery automation.');
    }
}
