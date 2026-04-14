<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MissingTimeLogsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->toDateString();

        $employeesWithoutLogs = User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'employee'))
            ->whereDoesntHave('timeLogs', fn ($query) => $query->whereDate('project_time_logs.start_time', $today))
            ->count();

        return [
            Stat::make('Missing Time Logs', (string) $employeesWithoutLogs)
                ->description('employees without logs today')
                ->color('danger'),
        ];
    }
}
