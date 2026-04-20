<?php

namespace Modules\FSMSaleRecurringAgreement\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMSaleRecurringAgreement\Console\Commands\SyncRecurringAgreementsCommand;

class FSMSaleRecurringAgreementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncRecurringAgreementsCommand::class,
            ]);
        }
    }

    public function register(): void {}
}
