<?php

namespace Modules\CustomerConnect\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Entities\Delivery;
use Modules\CustomerConnect\Enums\RunStatus;

class ExecuteCampaignRun implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $runId) {}

    public function handle(): void
    {
        $run = CampaignRun::find($this->runId);
        if (!$run) {
            return;
        }

        if ($run->status === RunStatus::Queued->value) {
            $run->status     = RunStatus::Running->value;
            $run->started_at = $run->started_at ?: Carbon::now();
            $run->save();
        }

        $now = Carbon::now();

        $dueDeliveries = Delivery::query()
            ->where('run_id', $run->id)
            ->whereIn('status', ['queued'])
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_for')->orWhere('scheduled_for', '<=', $now);
            })
            ->where('attempts', '<', 3)
            ->limit(200)
            ->get();

        foreach ($dueDeliveries as $delivery) {
            SendDelivery::dispatch($delivery->id);
        }

        $remaining = Delivery::query()
            ->where('run_id', $run->id)
            ->whereIn('status', ['queued', 'sending'])
            ->count();

        if ($remaining === 0) {
            $run->status      = RunStatus::Completed->value;
            $run->finished_at = $now;
            $run->save();
        }
    }
}
