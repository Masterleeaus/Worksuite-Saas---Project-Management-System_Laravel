<?php

namespace Modules\FSMRouteAvailability\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FSMBlackoutDay extends Model
{
    protected $table = 'fsm_blackout_days';

    protected $fillable = ['company_id', 'blackout_group_id', 'date', 'label', 'zip'];

    protected $casts = [
        'company_id'       => 'integer',
        'blackout_group_id' => 'integer',
        'date'             => 'date',
    ];

    public function group()
    {
        return $this->belongsTo(FSMBlackoutGroup::class, 'blackout_group_id');
    }

    /** Check whether this blackout day blocks the given date and optional zip code. */
    public function blocks(Carbon $date, ?string $zip = null): bool
    {
        if (!$this->date->isSameDay($date)) {
            return false;
        }
        if ($this->zip && $zip && $this->zip !== $zip) {
            return false;
        }
        return true;
    }
}
