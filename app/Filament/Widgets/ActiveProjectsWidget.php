<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveProjectsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        $companyId = auth()->user()?->company_id;

        $query = Project::query()->whereNotIn('status', ['finished', 'canceled']);

        if ($companyId) {
            $query->where('projects.company_id', $companyId);
        }

        return [
            Stat::make('Active Projects', (string) $query->count())
                ->description('Open projects in this tenant')
                ->color('success'),
        ];
    }
}
