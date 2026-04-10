<?php

namespace Modules\FSMStock\Providers;

use Illuminate\Support\ServiceProvider;

class FSMStockServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\FSMStock\Console\Commands\NotifyLowStock::class,
            ]);
        }
    }

    public function register(): void {}
}
