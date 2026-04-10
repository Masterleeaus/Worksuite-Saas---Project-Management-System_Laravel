<?php

namespace Modules\FSMTimesheet\Providers;

use Illuminate\Support\ServiceProvider;

class FSMTimesheetServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
