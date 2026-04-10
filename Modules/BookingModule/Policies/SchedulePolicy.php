<?php

namespace Modules\BookingModule\Policies;

use App\Models\User;

class SchedulePolicy
{
    public function manage(User $user): bool
    {
        return \Modules\BookingModule\Support\AppointmentPermission::check($user, 'schedule manage');
    }
}
