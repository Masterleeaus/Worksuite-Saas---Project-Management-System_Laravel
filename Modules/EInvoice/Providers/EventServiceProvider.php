<?php

namespace Modules\EInvoice\Providers;

use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\EInvoice\Listeners\CompanyCreatedListener;
use Modules\EInvoice\Listeners\CreateInvoiceFromBooking;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewCompanyCreatedEvent::class => [CompanyCreatedListener::class],
        BookingCompleted::class => [CreateInvoiceFromBooking::class],
    ];

    protected $observers = [

    ];
}
