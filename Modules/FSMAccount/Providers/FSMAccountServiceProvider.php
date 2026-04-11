<?php

namespace Modules\FSMAccount\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class FSMAccountServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMAccount', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMAccount', 'Config/config.php'), 'fsmaccount');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMAccount', 'Resources/views'), 'fsmaccount');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMAccount', 'Resources/lang'), 'fsmaccount');
    }
}
