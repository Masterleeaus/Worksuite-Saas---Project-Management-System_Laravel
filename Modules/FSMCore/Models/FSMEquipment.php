<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMEquipment extends Model
{
    protected $table = 'fsm_equipment';

    protected $fillable = [
        'company_id',
        'location_id',
        'name',
        'category',
        'notes',
        'warranty_expiry',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'company_id' => 'integer',
        'location_id' => 'integer',
        'warranty_expiry' => 'date',
    ];

    public function location()
    {
        return $this->belongsTo(FSMLocation::class, 'location_id');
    }

    public function orders()
    {
        return $this->belongsToMany(FSMOrder::class, 'fsm_order_equipment', 'fsm_equipment_id', 'fsm_order_id');
    }
}
