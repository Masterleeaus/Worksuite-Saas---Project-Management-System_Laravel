<?php

namespace Modules\FSMSize\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMSizeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMSize', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMSize', 'Config/config.php'), 'fsmsize');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMSize', 'Resources/views'), 'fsmsize');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMSize', 'Resources/lang'), 'fsmsize');
    }
}
