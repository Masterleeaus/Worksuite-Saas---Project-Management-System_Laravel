<?php

namespace Modules\FSMTerritory\Providers;

use Illuminate\Support\ServiceProvider;

class FSMTerritoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
