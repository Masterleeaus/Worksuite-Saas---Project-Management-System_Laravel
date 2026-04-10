<?php

namespace Modules\FSMPortal\Providers;

use Illuminate\Support\ServiceProvider;

class FSMPortalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
