<?php

namespace Modules\Biometric\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Biometric\Events\BiometricClockIn;
use Modules\Biometric\Listeners\AlertOnGeofenceFailure;
use Modules\Biometric\Listeners\NotifyClientOnCleanerArrival;
use Modules\Biometric\Listeners\NotifySupervisorOnClockIn;

class EventServiceProvider extends ServiceProvider
{
    protected $observers = [];

    protected $listen = [
        BiometricClockIn::class => [
            NotifySupervisorOnClockIn::class,
            NotifyClientOnCleanerArrival::class,
            AlertOnGeofenceFailure::class,
        ],
    ];
}
