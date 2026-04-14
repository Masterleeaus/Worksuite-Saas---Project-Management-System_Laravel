<?php

namespace App\Filament\Widgets;

use App\Models\Leave;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UpcomingAbsencesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $upcoming = Leave::query()
            ->where('status', 'approved')
            ->whereDate('leave_date', '>=', now()->toDateString())
            ->whereDate('leave_date', '<=', now()->addDays(7)->toDateString())
            ->count();

        return [
            Stat::make('Upcoming Absences (7d)', (string) $upcoming)
                ->description('approved leave in next 7 days')
                ->color('warning'),
        ];
    }
}
