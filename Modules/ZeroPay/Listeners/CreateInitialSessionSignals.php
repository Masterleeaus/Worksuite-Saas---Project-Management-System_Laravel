<?php

namespace Modules\ZeroPay\Listeners;

use Modules\ZeroPay\Events\PaymentSessionCreated;
use Modules\ZeroPay\Models\ZeroPayChannelLog;

class CreateInitialSessionSignals
{
    public function handle(PaymentSessionCreated $event): void
    {
        ZeroPayChannelLog::query()->create([
            'company_id' => $event->session->company_id,
            'session_id' => $event->session->id,
            'channel' => 'system',
            'direction' => 'outbound',
            'status' => 'logged',
            'subject' => 'Session created',
            'body' => 'ZeroPay session created and ready for channel orchestration.',
            'sent_at' => now(),
        ]);
    }
}
