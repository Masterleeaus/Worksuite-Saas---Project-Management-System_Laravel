<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Accountings\Services\TransactionAggregationService;

class MarginBySiteWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = (int) (auth()->user()?->company_id ?? 0);
        $sites = $companyId > 0 ? app(TransactionAggregationService::class)->marginBySite($companyId)->take(3) : collect();

        return $sites->map(fn ($row) => Stat::make((string) ($row->site_ref ?: 'Unmapped Site'), number_format((float) $row->total, 2)))->values()->all();
    }
}
