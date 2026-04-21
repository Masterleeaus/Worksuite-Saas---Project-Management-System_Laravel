<?php

namespace Modules\Accountings\Actions;

use Modules\Accountings\Entities\AccountingPeriod;
use Modules\Accountings\Events\PeriodClosed;
use Modules\Accountings\Services\AccountingSignalService;

class CloseAccountingPeriodAction
{
    public function __construct(private readonly AccountingSignalService $signalService)
    {
    }

    public function execute(int $companyId, string $periodKey, string $periodStart, string $periodEnd): AccountingPeriod
    {
        $period = AccountingPeriod::query()->updateOrCreate(
            ['company_id' => $companyId, 'period_key' => $periodKey],
            ['period_start' => $periodStart, 'period_end' => $periodEnd, 'status' => 'closed', 'closed_at' => now(), 'closed_by' => auth()->id(), 'meta' => []]
        );

        event(new PeriodClosed($companyId, ['period_id' => $period->id, 'period_key' => $period->period_key]));
        $this->signalService->emit('account.period.closed', $companyId, ['period_id' => $period->id, 'period_key' => $period->period_key]);

        return $period;
    }
}
