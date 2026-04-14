<?php

namespace App\Filament\Widgets;

use App\Models\ProjectNote;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecentProjectNotesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $last24h = ProjectNote::query()->where('created_at', '>=', now()->subDay())->count();

        return [
            Stat::make('Recent Notes (24h)', (string) $last24h)
                ->description('project notes and follow-ups')
                ->color('primary'),
        ];
    }
}
