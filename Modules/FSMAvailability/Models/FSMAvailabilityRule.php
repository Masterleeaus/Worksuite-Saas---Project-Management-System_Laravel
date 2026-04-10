<?php

namespace Modules\FSMAvailability\Models;

use Illuminate\Database\Eloquent\Model;

class FSMAvailabilityRule extends Model
{
    protected $table = 'fsm_availability_rules';

    protected $fillable = [
        'company_id',
        'person_id',
        'day_of_week',
        'time_start',
        'time_end',
        'active',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'person_id'  => 'integer',
        'active'     => 'boolean',
    ];

    /** Day-of-week labels keyed by enum value. */
    public static array $days = [
        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
        'sat' => 'Saturday',
        'sun' => 'Sunday',
    ];

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }
}
