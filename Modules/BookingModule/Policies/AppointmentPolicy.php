<?php

namespace Modules\BookingModule\Policies;

use App\Models\User;
use Modules\BookingModule\Entities\Appointment;

class AppointmentPolicy
{
    public function assign(User $user, Appointment $appointment): bool
    {
        return \Modules\BookingModule\Support\AppointmentPermission::check($user, 'appointments assign');
    }
}
