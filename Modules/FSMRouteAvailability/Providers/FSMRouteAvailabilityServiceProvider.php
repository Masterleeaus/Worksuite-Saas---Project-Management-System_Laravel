<?php

namespace Modules\FSMRouteAvailability\Providers;

use Illuminate\Support\ServiceProvider;

class FSMRouteAvailabilityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
