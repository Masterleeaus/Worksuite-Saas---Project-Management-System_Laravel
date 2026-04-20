<?php

namespace Modules\ZeroPay\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\ZeroPay\Models\ZeroPaySession;

class SessionsSentToday extends BaseWidget
{
    protected function getStats(): array
    {
        $count = ZeroPaySession::query()->whereDate('created_at', now()->toDateString())->count();

        return [
            Stat::make('Sessions Sent Today', (string) $count)->color('info'),
        ];
    }
}
