<?php

namespace Modules\FSMSales\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Modules\FSMSales\Events\RecurringInvoiceOverdue::class => [],
    ];
}
