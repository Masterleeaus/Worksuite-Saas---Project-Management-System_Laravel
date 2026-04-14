<?php

namespace App\Filament\Pages;

use App\Providers\TitanPanelProvider;
use Filament\Pages\Page;

abstract class TitanPage extends Page
{
    public static function canAccess(): bool
    {
        return TitanPanelProvider::canAccess();
    }
}
