<?php

namespace Modules\FSMCRM\Providers;

use Illuminate\Support\ServiceProvider;

class FSMCRMServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
