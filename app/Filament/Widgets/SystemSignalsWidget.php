<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * SystemSignalsWidget
 *
 * Placeholder widget for the Titan Command Centre.
 * Displays a summary of live system health signals.
 *
 * Logic to be implemented in a future Titan Zero sprint.
 */
class SystemSignalsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            Stat::make('Queue Health', '—')
                ->description('System signals pending implementation')
                ->color('gray'),

            Stat::make('Active Jobs', '—')
                ->description('Job signals pending implementation')
                ->color('gray'),

            Stat::make('Alerts', '—')
                ->description('Alert signals pending implementation')
                ->color('gray'),
        ];
    }
}
