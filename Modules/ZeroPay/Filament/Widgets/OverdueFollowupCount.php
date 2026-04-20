<?php

namespace Modules\ZeroPay\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\ZeroPay\Models\ZeroPayFollowup;

class OverdueFollowupCount extends BaseWidget
{
    protected function getStats(): array
    {
        $count = ZeroPayFollowup::query()
            ->whereIn('status', ['queued', 'overdue'])
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<', now())
            ->count();

        return [
            Stat::make('Overdue Follow-ups', (string) $count)->color('danger'),
        ];
    }
}
