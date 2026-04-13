<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * RevenueWidget
 *
 * Placeholder widget for the Titan Command Centre.
 * Displays a tenant-scoped revenue summary.
 *
 * Logic to be implemented in a future Titan Zero sprint.
 */
class RevenueWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Revenue Today', '—')
                ->description('Tenant-scoped revenue – pending implementation')
                ->color('gray'),

            Stat::make('Revenue This Month', '—')
                ->description('Monthly revenue – pending implementation')
                ->color('gray'),

            Stat::make('Outstanding Invoices', '—')
                ->description('Outstanding amount – pending implementation')
                ->color('gray'),
        ];
    }
}
