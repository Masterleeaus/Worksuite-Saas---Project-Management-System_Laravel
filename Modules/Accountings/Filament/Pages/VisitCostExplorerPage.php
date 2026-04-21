<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;

class VisitCostExplorerPage extends Page
{
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Visit Cost Explorer';
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static string $view = 'accountings::filament.pages.visit-cost-explorer';
}
