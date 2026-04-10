<?php

namespace Modules\FSMActivity\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMActivity\Console\Commands\NotifyOverdueActivities;

class FSMActivityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->commands([NotifyOverdueActivities::class]);
    }

    public function register(): void {}
}
