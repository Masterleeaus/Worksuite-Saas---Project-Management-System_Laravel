<?php

namespace Modules\FSMServiceAgreement\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMServiceAgreement\Console\Commands\GenerateAgreementOrdersCommand;

class FSMServiceAgreementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateAgreementOrdersCommand::class,
            ]);
        }
    }

    public function register(): void {}
}
