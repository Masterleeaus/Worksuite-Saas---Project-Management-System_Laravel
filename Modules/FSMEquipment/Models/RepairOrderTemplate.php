<?php

namespace Modules\FSMEquipment\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMEquipment;
use Modules\FSMCore\Models\FSMLocation;

class RepairOrderTemplate extends Model
{
    protected $table = 'fsm_repair_order_templates';

    protected $fillable = [
        'company_id',
        'name',
        'equipment_category',
        'description',
        'standard_parts',
        'estimated_hours',
    ];

    protected $casts = [
        'company_id'       => 'integer',
        'estimated_hours'  => 'decimal:2',
    ];

    public function repairOrders()
    {
        return $this->hasMany(RepairOrder::class, 'template_id');
    }
}
