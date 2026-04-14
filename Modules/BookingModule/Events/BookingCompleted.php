<?php

namespace Modules\BookingModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\BookingModule\Models\CleaningBooking;

class BookingCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public CleaningBooking $booking)
    {
    }
}
