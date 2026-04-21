<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;

class ContractProfitabilityPage extends Page
{
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Contract Profitability';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static string $view = 'accountings::filament.pages.contract-profitability';
}
