<?php

namespace Modules\FSMEquipment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMEquipment\Console\Commands\WarrantyExpiryAlertCommand;

class FSMEquipmentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                WarrantyExpiryAlertCommand::class,
            ]);
        }
    }

    public function register(): void {}
}
