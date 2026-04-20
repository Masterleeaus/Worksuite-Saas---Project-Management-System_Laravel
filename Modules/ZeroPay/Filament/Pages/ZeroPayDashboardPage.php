<?php

namespace Modules\ZeroPay\Filament\Pages;

use App\Filament\Pages\TitanPage;
use Modules\ZeroPay\Filament\Widgets\AwaitingConfirmationCount;
use Modules\ZeroPay\Filament\Widgets\BankMatchQueueCount;
use Modules\ZeroPay\Filament\Widgets\OverdueFollowupCount;
use Modules\ZeroPay\Filament\Widgets\SessionsPaidToday;
use Modules\ZeroPay\Filament\Widgets\SessionsSentToday;
use Modules\ZeroPay\Filament\Widgets\ZeroFeeVsPaidRailChart;

class ZeroPayDashboardPage extends TitanPage
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'ZeroPay Dashboard';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'zeropay::filament.pages.zeropay-dashboard-page';

    public function getHeaderWidgets(): array
    {
        return [
            SessionsSentToday::class,
            SessionsPaidToday::class,
            AwaitingConfirmationCount::class,
            BankMatchQueueCount::class,
            OverdueFollowupCount::class,
            ZeroFeeVsPaidRailChart::class,
        ];
    }
}
