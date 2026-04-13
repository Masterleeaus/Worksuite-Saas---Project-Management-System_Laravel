<?php

namespace Modules\TitanGo\Providers;

use Illuminate\Support\ServiceProvider;

class TitanGoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'titango');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Register Blade view namespace: titango::
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'titango');
    }
}
