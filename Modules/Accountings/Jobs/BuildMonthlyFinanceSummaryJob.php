<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\TransactionAggregationService;

class BuildMonthlyFinanceSummaryJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries   = 3;
    public int $timeout = 600;
    /** @var int[] */
    public array $backoff = [60, 300, 600];

    public function __construct(public readonly int $companyId)
    {
    }

    /** Only one summary build per company may be queued at a time. */
    public function uniqueId(): string
    {
        return "monthly_finance_summary_{$this->companyId}";
    }

    public function handle(TransactionAggregationService $transactionAggregationService): void
    {
        $transactionAggregationService->dailyRevenue($this->companyId);
        $transactionAggregationService->marginBySite($this->companyId);
    }
}
