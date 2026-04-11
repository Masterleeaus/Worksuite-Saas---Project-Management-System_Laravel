<?php

namespace Modules\FSMRepair\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMRepairServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMRepair', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMRepair', 'Config/config.php'), 'fsmrepair');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMRepair', 'Resources/views'), 'fsmrepair');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMRepair', 'Resources/lang'), 'fsmrepair');
    }
}
