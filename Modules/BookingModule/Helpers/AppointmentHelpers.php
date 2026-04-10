<?php

namespace Modules\BookingModule\Helpers;

use App\Models\User;

class AppointmentHelpers
{
    public static function staffOptions()
    {
        return User::where('created_by', creatorId())->orWhere('id', creatorId())->get();
    }
}
