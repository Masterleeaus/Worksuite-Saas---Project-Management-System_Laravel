<?php

namespace Modules\BookingModule\Observers;

use Modules\BookingModule\Entities\Appointment;

class AppointmentObserver
{
    public function creating(Appointment $appointment): void
    {
        if (empty($appointment->assignment_status)) {
            $appointment->assignment_status = $appointment->assigned_to ? 'assigned' : 'unassigned';
        }
    }

    public function updating(Appointment $appointment): void
    {
        if ($appointment->isDirty('assigned_to') && empty($appointment->assignment_status)) {
            $appointment->assignment_status = $appointment->assigned_to ? 'assigned' : 'unassigned';
        }
    }
}
