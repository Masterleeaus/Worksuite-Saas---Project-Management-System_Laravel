<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Jobs\DispatchFollowupJob;
use Modules\ZeroPay\Models\ZeroPayChannelLog;
use Modules\ZeroPay\Models\ZeroPayFollowup;
use Modules\ZeroPay\Models\ZeroPaySession;

class FollowupOrchestratorService
{
    public function queueEmail(ZeroPaySession $session, string $message): ZeroPayFollowup
    {
        $followup = ZeroPayFollowup::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'channel' => 'email',
            'status' => 'queued',
            'scheduled_at' => now(),
            'message' => $message,
            'ai_suggested' => true,
        ]);

        ZeroPayChannelLog::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'followup_id' => $followup->id,
            'channel' => 'email',
            'status' => 'queued',
            'subject' => 'ZeroPay follow-up',
            'body' => $message,
        ]);

        DispatchFollowupJob::dispatch($followup->id);

        return $followup;
    }
}
