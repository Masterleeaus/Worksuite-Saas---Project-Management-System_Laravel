<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Accountings\Entities\ProfitabilitySnapshot;

class ContractsAtRiskWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = (int) (auth()->user()?->company_id ?? 0);
        $count = $companyId > 0
            ? ProfitabilitySnapshot::query()->where('company_id', $companyId)->where('scope_type', 'contract')->where('margin_percent', '<', 0)->count()
            : 0;

        return [Stat::make('Contracts at Risk', (string) $count)];
    }
}
