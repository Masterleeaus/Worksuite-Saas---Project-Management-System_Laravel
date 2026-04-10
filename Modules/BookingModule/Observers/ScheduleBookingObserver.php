<?php

namespace Modules\BookingModule\Observers;

use Modules\BookingModule\Entities\Schedule;

class ScheduleBookingObserver
{
    public function saving(Schedule $schedule): void
    {
        // Backfill starts_at/ends_at from legacy date + start_time/end_time if not set.
        if (!$schedule->starts_at && $schedule->date && $schedule->start_time) {
            $schedule->starts_at = $schedule->date.' '.$schedule->start_time;
        }
        if (!$schedule->ends_at && $schedule->date && $schedule->end_time) {
            $schedule->ends_at = $schedule->date.' '.$schedule->end_time;
        }
    }
}
