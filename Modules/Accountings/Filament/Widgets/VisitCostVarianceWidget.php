<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Accountings\Entities\VisitCost;

class VisitCostVarianceWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = (int) (auth()->user()?->company_id ?? 0);
        $variance = $companyId > 0 ? (float) VisitCost::query()->where('company_id', $companyId)->sum('variance_amount') : 0;

        return [Stat::make('Visit Cost Variance', number_format($variance, 2))];
    }
}
