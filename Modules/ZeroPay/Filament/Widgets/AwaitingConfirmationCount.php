<?php

namespace Modules\ZeroPay\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\ZeroPay\Models\ZeroPaySession;

class AwaitingConfirmationCount extends BaseWidget
{
    protected function getStats(): array
    {
        $count = ZeroPaySession::query()->where('status', 'awaiting_confirmation')->count();

        return [
            Stat::make('Awaiting Confirmation', (string) $count)->color('warning'),
        ];
    }
}
