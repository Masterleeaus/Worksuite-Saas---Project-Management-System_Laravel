<?php

namespace Modules\FSMAvailability\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FSMAvailabilityException extends Model
{
    protected $table = 'fsm_availability_exceptions';

    protected $fillable = [
        'company_id',
        'person_id',
        'date_start',
        'date_end',
        'reason',
        'notes',
        'approved_by',
        'state',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'person_id'  => 'integer',
        'approved_by' => 'integer',
        'date_start'  => 'datetime',
        'date_end'    => 'datetime',
    ];

    /** Reason labels keyed by enum value. */
    public static array $reasons = [
        'leave'          => 'Annual Leave',
        'sick'           => 'Sick Leave',
        'public_holiday' => 'Public Holiday',
        'training'       => 'Training',
        'other'          => 'Other',
    ];

    /** State labels keyed by enum value. */
    public static array $states = [
        'pending'  => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    public function person()
    {
        return $this->belongsTo(\App\Models\User::class, 'person_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    /** True when this exception overlaps the given datetime range. */
    public function overlaps(Carbon $start, Carbon $end): bool
    {
        return $this->date_start < $end && $this->date_end > $start;
    }
}
