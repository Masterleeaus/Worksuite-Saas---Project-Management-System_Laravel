<?php

namespace Modules\FSMServiceAgreement\Providers;

use Illuminate\Support\ServiceProvider;

class FSMServiceAgreementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
