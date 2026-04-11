<?php

namespace Modules\FSMRepairTemplate\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMRepairTemplateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMRepairTemplate', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMRepairTemplate', 'Config/config.php'), 'fsmrepairtemplate');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMRepairTemplate', 'Resources/views'), 'fsmrepairtemplate');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMRepairTemplate', 'Resources/lang'), 'fsmrepairtemplate');
    }
}
