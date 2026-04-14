<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UnpaidInvoicesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $count = Invoice::query()->whereIn('status', ['unpaid', 'partial'])->count();

        return [
            Stat::make('Unpaid Invoices', (string) $count)->color('danger'),
        ];
    }
}
