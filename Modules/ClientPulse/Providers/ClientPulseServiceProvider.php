<?php

namespace Modules\ClientPulse\Providers;

use Illuminate\Support\ServiceProvider;

class ClientPulseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
