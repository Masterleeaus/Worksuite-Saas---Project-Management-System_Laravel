<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Events\AppointmentStatus;
use Modules\BookingModule\Jobs\RecalculateScheduleCapacityJob;

/**
 * AppointmentStatusListener
 *
 * Triggers a capacity recalculation whenever an AppointmentStatus event fires
 * (e.g. a schedule is approved, declined, or cancelled).
 */
class AppointmentStatusListener
{
    public function handle(AppointmentStatus $event): void
    {
        $schedule = $event->schedule;

        if (!$schedule || !isset($schedule->id)) {
            return;
        }

        $companyId = $schedule->company_id ?? 0;

        RecalculateScheduleCapacityJob::dispatch($schedule->id, $companyId)
            ->onQueue('default');
    }
}
