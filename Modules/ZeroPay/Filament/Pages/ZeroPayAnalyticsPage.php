<?php

namespace Modules\ZeroPay\Filament\Pages;

use App\Filament\Pages\TitanPage;
use Modules\ZeroPay\Filament\Widgets\ZeroFeeVsPaidRailChart;

class ZeroPayAnalyticsPage extends TitanPage
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'zeropay::filament.pages.zeropay-analytics-page';

    public function getHeaderWidgets(): array
    {
        return [
            ZeroFeeVsPaidRailChart::class,
        ];
    }
}
