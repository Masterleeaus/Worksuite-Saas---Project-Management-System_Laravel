<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'inventory');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'inventory');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'inventory');

        // Publish config (optional)
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('inventory.php'),
        ], 'inventory-config');
    }
}
