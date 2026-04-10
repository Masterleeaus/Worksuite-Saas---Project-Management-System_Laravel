<?php

namespace Modules\Suppliers\Providers;

use Illuminate\Support\ServiceProvider;

class SuppliersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'suppliers');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        if (file_exists(__DIR__ . '/../Routes/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        }
        if (is_dir(__DIR__ . '/../Resources/views')) {
            $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'suppliers');
        }
        if (is_dir(__DIR__ . '/../Resources/lang')) {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'suppliers');
        }
    }
}
