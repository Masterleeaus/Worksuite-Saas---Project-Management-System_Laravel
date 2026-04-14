<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeesActiveTodayWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->toDateString();

        $active = Attendance::query()
            ->whereDate('clock_in_time', $today)
            ->distinct('user_id')
            ->count('user_id');

        $employees = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'employee'))
            ->count();

        return [
            Stat::make('Employees Active Today', (string) $active)
                ->description('of ' . $employees . ' employees')
                ->color('success'),
        ];
    }
}
