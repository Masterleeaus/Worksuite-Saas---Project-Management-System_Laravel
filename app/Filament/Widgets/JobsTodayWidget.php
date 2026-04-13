<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * JobsTodayWidget
 *
 * Placeholder widget for the Titan Command Centre.
 * Displays a summary of today's jobs scoped to the authenticated tenant.
 *
 * Logic to be implemented in a future Titan Zero sprint.
 */
class JobsTodayWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Jobs Today', '—')
                ->description('Tenant-scoped jobs count – pending implementation')
                ->color('gray'),

            Stat::make('Completed', '—')
                ->description('Completed jobs today – pending implementation')
                ->color('gray'),

            Stat::make('Pending', '—')
                ->description('Pending jobs today – pending implementation')
                ->color('gray'),
        ];
    }
}
