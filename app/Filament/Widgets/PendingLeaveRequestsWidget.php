<?php

namespace App\Filament\Widgets;

use App\Models\Leave;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingLeaveRequestsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $pending = Leave::query()->where('status', 'pending')->count();

        return [
            Stat::make('Leave Requests Pending', (string) $pending)
                ->description('awaiting approval')
                ->color('warning'),
        ];
    }
}
