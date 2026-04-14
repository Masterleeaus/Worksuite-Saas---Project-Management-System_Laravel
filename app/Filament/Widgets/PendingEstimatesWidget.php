<?php

namespace App\Filament\Widgets;

use App\Models\Estimate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingEstimatesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Estimate::query()->whereIn('status', ['waiting', 'sent', 'draft'])->count();

        return [
            Stat::make('Pending Estimates', (string) $count)->color('warning'),
        ];
    }
}
