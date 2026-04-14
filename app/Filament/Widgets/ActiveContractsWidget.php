<?php

namespace App\Filament\Widgets;

use App\Models\Contract;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveContractsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Contract::query()
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhereDate('end_date', '>=', now()->toDateString());
            })
            ->count();

        return [
            Stat::make('Active Contracts', (string) $count)->color('primary'),
        ];
    }
}
