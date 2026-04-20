<?php

namespace Modules\ZeroPay\Actions;

use Modules\ZeroPay\Models\ZeroPayFollowup;
use Modules\ZeroPay\Models\ZeroPaySession;
use Modules\ZeroPay\Models\ZeroPayVoiceRun;

class QueueVoiceFollowupAction
{
    public function execute(ZeroPaySession $session): ZeroPayVoiceRun
    {
        $followup = ZeroPayFollowup::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'channel' => 'voice',
            'status' => 'queued',
            'scheduled_at' => now(),
            'message' => 'Voice follow-up queued by operator.',
        ]);

        return ZeroPayVoiceRun::query()->create([
            'company_id' => $session->company_id,
            'session_id' => $session->id,
            'followup_id' => $followup->id,
            'provider' => (string) config('zeropay.voice.provider', 'twilio'),
            'status' => 'queued',
            'meta' => ['queued_by' => auth()->id()],
        ]);
    }
}
