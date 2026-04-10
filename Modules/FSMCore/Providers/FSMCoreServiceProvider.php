<?php

namespace Modules\FSMCore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class FSMCoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Migrations are loaded by RouteServiceProvider / module loader.
        // Views are loaded by ViewServiceProvider.
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
