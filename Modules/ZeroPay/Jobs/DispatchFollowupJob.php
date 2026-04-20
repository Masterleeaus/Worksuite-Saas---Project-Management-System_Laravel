<?php

namespace Modules\ZeroPay\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\ZeroPay\Models\ZeroPayFollowup;

class DispatchFollowupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly int $followupId)
    {
    }

    public function handle(): void
    {
        $followup = ZeroPayFollowup::query()->find($this->followupId);

        if (!$followup || $followup->status !== 'queued') {
            return;
        }

        $followup->status = 'sent';
        $followup->sent_at = now();
        $followup->save();
    }
}
