<?php

namespace Modules\ZeroPay\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // TitanPanelProvider handles discovery from Modules/ZeroPay/Filament/*.
    }
}
