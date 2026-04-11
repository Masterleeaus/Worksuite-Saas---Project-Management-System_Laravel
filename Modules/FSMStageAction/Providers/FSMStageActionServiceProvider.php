<?php

namespace Modules\FSMStageAction\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMStageActionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMStageAction', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMStageAction', 'Config/config.php'), 'fsmstageaction');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMStageAction', 'Resources/views'), 'fsmstageaction');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMStageAction', 'Resources/lang'), 'fsmstageaction');
    }
}
