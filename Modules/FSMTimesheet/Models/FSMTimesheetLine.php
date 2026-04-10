<?php

namespace Modules\FSMTimesheet\Models;

use Illuminate\Database\Eloquent\Model;

class FSMTimesheetLine extends Model
{
    protected $table = 'fsm_timesheet_lines';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'user_id',
        'date',
        'name',
        'unit_amount',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'fsm_order_id' => 'integer',
        'user_id'      => 'integer',
        'date'         => 'date',
        'unit_amount'  => 'decimal:2',
    ];

    /**
     * Hours auto-computed from start_time and end_time.
     * Returns null when either time is not set.
     */
    public function getComputedHoursAttribute(): ?float
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        [$sh, $sm] = array_map('intval', explode(':', substr($this->start_time, 0, 5)));
        [$eh, $em] = array_map('intval', explode(':', substr($this->end_time, 0, 5)));

        $startMinutes = $sh * 60 + $sm;
        $endMinutes   = $eh * 60 + $em;
        $diffMinutes  = $endMinutes - $startMinutes;

        if ($diffMinutes <= 0) {
            return null;
        }

        return round($diffMinutes / 60, 2);
    }

    /**
     * Automatically sync unit_amount from start/end times when both are present.
     */
    protected static function booted(): void
    {
        static::saving(function (self $line) {
            $computed = $line->computedHours;
            if ($computed !== null) {
                $line->unit_amount = $computed;
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
