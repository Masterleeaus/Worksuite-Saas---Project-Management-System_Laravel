<?php

namespace Modules\CustomerConnect\Services;

use Carbon\Carbon;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Enums\RunStatus;

class CampaignScheduler
{
    /**
     * Find runs that should be executed now.
     */
    public function dueRuns(int $limit = 50)
    {
        return CampaignRun::query()
            ->whereIn('status', [RunStatus::Queued->value, RunStatus::Running->value])
            ->where(function ($q) {
                $q->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', Carbon::now());
            })
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();
    }
}
