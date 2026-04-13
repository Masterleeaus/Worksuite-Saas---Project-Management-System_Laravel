<?php

namespace Modules\NexusFieldOps\Providers;

use Illuminate\Support\ServiceProvider;

class NexusFieldOpsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
