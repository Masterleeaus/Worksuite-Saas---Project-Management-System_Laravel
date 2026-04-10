<?php

namespace Modules\BookingModule\Observers;

use Modules\BookingModule\Entities\Schedule;

class ScheduleObserver
{
    public function saving(Schedule $schedule): void
    {
        // placeholder for availability normalization in later passes
    }
}
