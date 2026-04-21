<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Services\TransactionAggregationService;

class BuildMonthlyFinanceSummaryJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly int $companyId)
    {
    }

    public function handle(TransactionAggregationService $transactionAggregationService): void
    {
        $transactionAggregationService->dailyRevenue($this->companyId);
        $transactionAggregationService->marginBySite($this->companyId);
    }
}
