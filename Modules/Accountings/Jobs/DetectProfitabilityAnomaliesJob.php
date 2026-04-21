<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\CleaningMarginAnalyticsService;

class DetectProfitabilityAnomaliesJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries   = 3;
    public int $timeout = 300;
    /** @var int[] */
    public array $backoff = [30, 120, 300];

    public function __construct(public readonly int $companyId)
    {
    }

    /** Only one anomaly-detection pass per company queued at a time. */
    public function uniqueId(): string
    {
        return "detect_anomalies_{$this->companyId}";
    }

    public function handle(CleaningMarginAnalyticsService $cleaningMarginAnalyticsService): void
    {
        $cleaningMarginAnalyticsService->detectProfitabilityAnomalies($this->companyId);
    }
}
