<?php

namespace Modules\FSMActivity\Models;

use Illuminate\Database\Eloquent\Model;

class FSMActivity extends Model
{
    protected $table = 'fsm_activities';

    public const STATES = [
        'open'      => 'Open',
        'done'      => 'Done',
        'cancelled' => 'Cancelled',
        'overdue'   => 'Overdue',
    ];

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'activity_type_id',
        'summary',
        'note',
        'due_date',
        'assigned_to',
        'state',
        'done_at',
        'done_by',
    ];

    protected $casts = [
        'due_date'         => 'date',
        'done_at'          => 'datetime',
        'company_id'       => 'integer',
        'fsm_order_id'     => 'integer',
        'activity_type_id' => 'integer',
        'assigned_to'      => 'integer',
        'done_by'          => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function activityType()
    {
        return $this->belongsTo(FSMActivityType::class, 'activity_type_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function doneByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'done_by');
    }

    public function isOverdue(): bool
    {
        return $this->state === 'open'
            && $this->due_date !== null
            && $this->due_date->isPast()
            && !$this->due_date->isToday();
    }

    public function scopeOpen($q)
    {
        return $q->where('state', 'open');
    }

    public function scopeOverdue($q)
    {
        return $q->whereIn('state', ['open', 'overdue'])
                 ->whereDate('due_date', '<', now()->toDateString());
    }
}
