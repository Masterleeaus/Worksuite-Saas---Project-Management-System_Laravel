<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Accountings\Services\TransactionAggregationService;

class UnreconciledPaymentsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = (int) (auth()->user()?->company_id ?? 0);
        $count = $companyId > 0 ? app(TransactionAggregationService::class)->unreconciledPayments($companyId) : 0;

        return [Stat::make('Unreconciled Payments', (string) $count)];
    }
}
