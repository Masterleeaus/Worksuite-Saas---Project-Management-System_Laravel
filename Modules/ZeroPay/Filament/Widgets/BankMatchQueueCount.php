<?php

namespace Modules\ZeroPay\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\ZeroPay\Models\ZeroPayBankMatch;

class BankMatchQueueCount extends BaseWidget
{
    protected function getStats(): array
    {
        $count = ZeroPayBankMatch::query()->where('status', 'queued')->count();

        return [
            Stat::make('Bank Match Queue', (string) $count)->color('warning'),
        ];
    }
}
