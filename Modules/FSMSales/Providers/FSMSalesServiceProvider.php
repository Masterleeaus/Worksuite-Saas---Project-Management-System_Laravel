<?php

namespace Modules\FSMSales\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\FSMSales\Console\Commands\GenerateRecurringInvoices;
use Modules\FSMSales\Console\Commands\NotifyOverdueInvoices;

class FSMSalesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateRecurringInvoices::class,
                NotifyOverdueInvoices::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->app->singleton(
            \Modules\FSMSales\Services\InvoiceGenerationService::class
        );
    }
}
