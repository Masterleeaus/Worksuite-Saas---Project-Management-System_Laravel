<?php

namespace Modules\FSMSaleStock\Providers;

use Illuminate\Support\ServiceProvider;

class FSMSaleStockServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void {}
}
