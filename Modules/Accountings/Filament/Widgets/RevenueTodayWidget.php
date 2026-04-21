<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Accountings\Services\TransactionAggregationService;

class RevenueTodayWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = (int) (auth()->user()?->company_id ?? 0);
        $amount = $companyId > 0 ? app(TransactionAggregationService::class)->dailyRevenue($companyId) : 0;

        return [
            Stat::make('Revenue Today', number_format($amount, 2)),
        ];
    }
}
