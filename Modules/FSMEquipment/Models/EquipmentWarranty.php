<?php

namespace Modules\FSMEquipment\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMEquipment;

class EquipmentWarranty extends Model
{
    protected $table = 'fsm_equipment_warranties';

    protected $fillable = [
        'company_id',
        'equipment_id',
        'warranty_start',
        'warranty_end',
        'supplier',
        'warranty_number',
        'notes',
    ];

    protected $casts = [
        'company_id'     => 'integer',
        'equipment_id'   => 'integer',
        'warranty_start' => 'date',
        'warranty_end'   => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(FSMEquipment::class, 'equipment_id');
    }

    /**
     * Returns 'active', 'expiring_soon' (within 14 days), or 'expired'.
     */
    public function warrantyStatus(): string
    {
        $today = now()->startOfDay();
        if ($this->warranty_end->lt($today)) {
            return 'expired';
        }
        $daysLeft = (int) $today->diffInDays($this->warranty_end);
        if ($daysLeft <= 14) {
            return 'expiring_soon';
        }
        return 'active';
    }
}
