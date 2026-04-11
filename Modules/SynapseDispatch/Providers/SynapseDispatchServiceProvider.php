<?php

namespace Modules\SynapseDispatch\Providers;

use Illuminate\Support\ServiceProvider;

class SynapseDispatchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
