<?php

namespace Modules\BookingModule\Services\AutoAssign\Strategies;

use Modules\BookingModule\Entities\Appointment;

interface AutoAssignStrategy
{
    /**
     * Return user id to assign the appointment to, or null to leave unassigned.
     */
    public function pickAssigneeUserId(Appointment $appointment): ?int;
}
