<?php

namespace Modules\CustomerConnect\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Services\CampaignRunBuilder;

class BuildCampaignRun implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $runId) {}

    public function handle(CampaignRunBuilder $builder): void
    {
        $run = CampaignRun::find($this->runId);
        if (!$run) {
            return;
        }
        $builder->build($run);
    }
}
