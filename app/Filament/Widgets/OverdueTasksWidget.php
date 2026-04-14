<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverdueTasksWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Task::query()
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->count();

        return [
            Stat::make('Overdue Tasks', (string) $count)->color('danger'),
        ];
    }
}
