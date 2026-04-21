<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;

class SiteProfitabilityPage extends Page
{
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Site Profitability';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static string $view = 'accountings::filament.pages.site-profitability';
}
