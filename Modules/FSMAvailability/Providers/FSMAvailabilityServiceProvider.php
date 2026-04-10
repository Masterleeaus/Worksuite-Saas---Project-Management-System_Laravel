<?php

namespace Modules\FSMAvailability\Providers;

use Illuminate\Support\ServiceProvider;

class FSMAvailabilityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\FSMAvailability\Console\Commands\ImportPublicHolidays::class,
            ]);
        }
    }

    public function register(): void {}
}
