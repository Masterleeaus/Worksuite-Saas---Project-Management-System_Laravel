<?php

namespace Modules\FSMProject\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMProjectServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMProject', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMProject', 'Config/config.php'), 'fsmproject');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMProject', 'Resources/views'), 'fsmproject');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMProject', 'Resources/lang'), 'fsmproject');
    }
}
