<?php

namespace Modules\ZeroPay\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\ZeroPay\Models\ZeroPaySession;

class SessionsPaidToday extends BaseWidget
{
    protected function getStats(): array
    {
        $count = ZeroPaySession::query()->whereDate('paid_at', now()->toDateString())->count();

        return [
            Stat::make('Sessions Paid Today', (string) $count)->color('success'),
        ];
    }
}
