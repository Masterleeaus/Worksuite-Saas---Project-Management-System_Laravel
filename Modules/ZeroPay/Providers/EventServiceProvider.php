<?php

namespace Modules\ZeroPay\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\ZeroPay\Events\PaymentSessionCreated;
use Modules\ZeroPay\Listeners\CreateInitialSessionSignals;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentSessionCreated::class => [
            CreateInitialSessionSignals::class,
        ],
    ];
}
