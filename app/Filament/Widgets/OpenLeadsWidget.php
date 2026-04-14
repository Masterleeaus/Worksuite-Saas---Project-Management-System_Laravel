<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OpenLeadsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Lead::query()->where('next_follow_up', 'yes')->count();

        return [
            Stat::make('Open Leads', (string) $count)->color('info'),
        ];
    }
}
