<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Accountings\Entities\ProfitabilitySnapshot;

class ProfitabilityTrendWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = (int) (auth()->user()?->company_id ?? 0);
        $latest = $companyId > 0 ? ProfitabilitySnapshot::query()->where('company_id', $companyId)->latest('id')->first() : null;

        return [
            Stat::make('Latest Margin %', number_format((float) ($latest?->margin_percent ?? 0), 2)),
        ];
    }
}
