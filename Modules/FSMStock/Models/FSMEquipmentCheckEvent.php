<?php

namespace Modules\FSMStock\Models;

use Illuminate\Database\Eloquent\Model;

class FSMEquipmentCheckEvent extends Model
{
    public const EVENT_CHECK_IN  = 'check_in';
    public const EVENT_CHECK_OUT = 'check_out';

    protected $table = 'fsm_equipment_check_events';

    protected $fillable = [
        'company_id',
        'register_id',
        'fsm_order_id',
        'checked_by',
        'event_type',
        'notes',
        'checked_at',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'register_id'  => 'integer',
        'fsm_order_id' => 'integer',
        'checked_by'   => 'integer',
        'checked_at'   => 'datetime',
    ];

    public function register()
    {
        return $this->belongsTo(FSMLocationEquipmentRegister::class, 'register_id');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function checker()
    {
        return $this->belongsTo(\App\Models\User::class, 'checked_by');
    }
}
