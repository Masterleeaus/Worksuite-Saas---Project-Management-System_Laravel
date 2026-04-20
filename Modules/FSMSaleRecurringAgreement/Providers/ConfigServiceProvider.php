<?php

namespace Modules\FSMSaleRecurringAgreement\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'fsmsalerecurringagreement');
    }
}
