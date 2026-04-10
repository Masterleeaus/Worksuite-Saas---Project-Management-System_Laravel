<?php

namespace Modules\FSMRoute\Providers;

use Illuminate\Support\ServiceProvider;

class FSMRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
