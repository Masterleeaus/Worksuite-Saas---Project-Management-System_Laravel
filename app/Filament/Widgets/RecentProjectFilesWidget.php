<?php

namespace App\Filament\Widgets;

use App\Models\ProjectFile;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecentProjectFilesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $companyId = auth()->user()?->company_id;

        $last7d = ProjectFile::query()
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return [
            Stat::make('Recent Files (7d)', (string) $last7d)
                ->description('new project documents')
                ->color('gray'),
        ];
    }
}
