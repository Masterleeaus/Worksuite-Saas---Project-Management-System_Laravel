<?php

namespace Modules\FSMVehicle\Providers;

use Illuminate\Support\ServiceProvider;

class FSMVehicleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
