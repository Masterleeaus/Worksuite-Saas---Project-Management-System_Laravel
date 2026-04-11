<?php

namespace Modules\FSMKanban\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMKanbanServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMKanban', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMKanban', 'Config/config.php'), 'fsmkanban');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMKanban', 'Resources/views'), 'fsmkanban');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMKanban', 'Resources/lang'), 'fsmkanban');
    }
}
