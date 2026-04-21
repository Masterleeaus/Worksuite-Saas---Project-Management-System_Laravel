<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\CleaningMarginAnalyticsService;

class DetectProfitabilityAnomaliesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly int $companyId)
    {
    }

    public function handle(CleaningMarginAnalyticsService $cleaningMarginAnalyticsService): void
    {
        $cleaningMarginAnalyticsService->detectProfitabilityAnomalies($this->companyId);
    }
}
