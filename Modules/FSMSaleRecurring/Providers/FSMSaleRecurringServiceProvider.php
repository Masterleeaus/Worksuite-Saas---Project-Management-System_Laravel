<?php

namespace Modules\FSMSaleRecurring\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMSaleRecurring\Console\Commands\SyncSaleRecurringCommand;

class FSMSaleRecurringServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void
    {
        $this->commands([
            SyncSaleRecurringCommand::class,
        ]);
    }
}
