<?php

namespace Modules\ClientPulse\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMOrder;

class ExtrasRequest extends Model
{
    protected $table = 'client_pulse_extras_requests';

    public const STATUS_PENDING      = 'pending';
    public const STATUS_ACKNOWLEDGED = 'acknowledged';
    public const STATUS_ADDED        = 'added_to_job';

    protected $fillable = [
        'company_id',
        'client_id',
        'fsm_order_id',
        'items',
        'custom_note',
        'status',
        'acknowledged_at',
        'acknowledged_by',
    ];

    protected $casts = [
        'company_id'      => 'integer',
        'client_id'       => 'integer',
        'fsm_order_id'    => 'integer',
        'items'           => 'array',
        'acknowledged_at' => 'datetime',
        'acknowledged_by' => 'integer',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id');
    }

    public function order()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function acknowledgedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'acknowledged_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
