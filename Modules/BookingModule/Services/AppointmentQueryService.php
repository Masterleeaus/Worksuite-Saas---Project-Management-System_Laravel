<?php

namespace Modules\BookingModule\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\BookingModule\Entities\Appointment;

class AppointmentQueryService
{
    public function base(): Builder
    {
        $q = Appointment::query();
        if (function_exists('getActiveWorkSpace')) {
            $q->where('workspace', getActiveWorkSpace());
        }
        return $q;
    }

    public function unassigned(): Builder
    {
        return $this->base()->unassigned();
    }

    public function mine(int $userId): Builder
    {
        return $this->base()->assignedTo($userId);
    }
}
