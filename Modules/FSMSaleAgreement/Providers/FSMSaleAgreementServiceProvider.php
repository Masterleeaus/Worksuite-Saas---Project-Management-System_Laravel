<?php

namespace Modules\FSMSaleAgreement\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMSaleAgreement\Console\Commands\SyncSaleAgreementsCommand;

class FSMSaleAgreementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncSaleAgreementsCommand::class,
            ]);
        }
    }

    public function register(): void {}
}
