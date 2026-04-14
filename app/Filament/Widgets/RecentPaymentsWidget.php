<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecentPaymentsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $amount = Payment::query()
            ->where('status', 'complete')
            ->whereDate('paid_on', '>=', now()->subDays(7)->toDateString())
            ->sum('amount');

        return [
            Stat::make('Recent Payments (7d)', number_format((float) $amount, 2))->color('success'),
        ];
    }
}
