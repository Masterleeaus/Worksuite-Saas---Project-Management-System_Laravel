<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClockedInTodayWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->toDateString();
        $companyId = auth()->user()?->company_id;

        $clockedIn = Attendance::query()
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
            ->whereDate('clock_in_time', $today)
            ->whereNull('clock_out_time')
            ->distinct('user_id')
            ->count('user_id');

        return [
            Stat::make('Clocked In Now', (string) $clockedIn)
                ->description('active attendance sessions')
                ->color('info'),
        ];
    }
}
