<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;

class PeriodClosePage extends Page
{
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Period Close';
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static string $view = 'accountings::filament.pages.period-close';
}
