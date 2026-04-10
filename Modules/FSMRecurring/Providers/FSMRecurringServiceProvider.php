<?php

namespace Modules\FSMRecurring\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\FSMRecurring\Console\Commands\GenerateRecurringOrders;

class FSMRecurringServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([GenerateRecurringOrders::class]);
        }

        // Schedule daily generation of recurring orders
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('fsm:recurring:generate')
                ->dailyAt('02:00')
                ->withoutOverlapping()
                ->runInBackground();
        });
    }

    public function register(): void {}
}
