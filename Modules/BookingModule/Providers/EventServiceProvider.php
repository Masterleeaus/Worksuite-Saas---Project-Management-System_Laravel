<?php

namespace Modules\BookingModule\Providers;

use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\BookingModule\Events\BookingRequested;
use Modules\BookingModule\Listeners\CompanyCreatedListener;
use Modules\BookingModule\Listeners\EmitBookingRequestedSignalToTitanZero;
use Modules\BookingModule\Listeners\SendBookingRequestEmail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewCompanyCreatedEvent::class => [
            CompanyCreatedListener::class,
        ],
        BookingRequested::class => [
            SendBookingRequestEmail::class,
            EmitBookingRequestedSignalToTitanZero::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
