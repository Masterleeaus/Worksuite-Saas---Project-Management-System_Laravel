<?php

namespace Modules\FSMCalendar\Providers;

use Illuminate\Support\ServiceProvider;

class FSMCalendarServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('FSMCalendar', 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(module_path('FSMCalendar', 'Config/config.php'), 'fsmcalendar');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path('FSMCalendar', 'Resources/views'), 'fsmcalendar');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path('FSMCalendar', 'Resources/lang'), 'fsmcalendar');
    }
}
