<?php

namespace Modules\BookingModule\Services\AutoAssign\Strategies;

use Modules\BookingModule\Entities\Appointment;
use Modules\BookingModule\Entities\AppointmentSetting;

class RoundRobinStrategy extends BaseStrategy
{
    public function pickAssigneeUserId(Appointment $appointment): ?int
    {
        $candidates = $this->eligibleUserIds($appointment);
        if (!$candidates) {
            return null;
        }

        // Persist pointer in settings for fairness.
        $key = 'auto_assign.round_robin.pointer';
        $pointer = (int)($this->settings->get($key, 0));

        $index = $pointer % count($candidates);
        $pick = $candidates[$index] ?? null;

        $this->settings->set($key, $pointer + 1);

        return $pick ? (int)$pick : null;
    }
}
