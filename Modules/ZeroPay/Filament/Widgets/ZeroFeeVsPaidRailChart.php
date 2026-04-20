<?php

namespace Modules\ZeroPay\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\ZeroPay\Models\ZeroPayAttempt;

class ZeroFeeVsPaidRailChart extends ChartWidget
{
    protected static ?string $heading = 'Zero-fee vs Processing-fee Rails';

    protected function getData(): array
    {
        $zeroFee = ZeroPayAttempt::query()->where('rail_type', 'zero_fee')->whereDate('created_at', '>=', now()->subDays(30)->toDateString())->count();
        $paid = ZeroPayAttempt::query()->where('rail_type', 'processing_fee')->whereDate('created_at', '>=', now()->subDays(30)->toDateString())->count();

        return [
            'datasets' => [
                [
                    'label' => 'Attempts (30d)',
                    'data' => [$zeroFee, $paid],
                    'backgroundColor' => ['#10b981', '#f59e0b'],
                ],
            ],
            'labels' => ['Zero-fee rails', 'Processing-fee rails'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
