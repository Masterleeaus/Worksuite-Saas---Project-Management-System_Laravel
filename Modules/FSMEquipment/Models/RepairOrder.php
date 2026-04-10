<?php

namespace Modules\FSMEquipment\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMEquipment;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;

class RepairOrder extends Model
{
    protected $table = 'fsm_repair_orders';

    protected $fillable = [
        'company_id',
        'name',
        'equipment_id',
        'fsm_location_id',
        'template_id',
        'fsm_order_id',
        'description',
        'reported_by',
        'assigned_to',
        'priority',
        'date_reported',
        'date_scheduled',
        'date_completed',
        'stage',
        'root_cause',
        'cost',
        'parts_used',
        'under_warranty',
    ];

    protected $casts = [
        'company_id'     => 'integer',
        'equipment_id'   => 'integer',
        'fsm_location_id'=> 'integer',
        'template_id'    => 'integer',
        'fsm_order_id'   => 'integer',
        'reported_by'    => 'integer',
        'assigned_to'    => 'integer',
        'date_reported'  => 'datetime',
        'date_scheduled' => 'datetime',
        'date_completed' => 'datetime',
        'cost'           => 'decimal:2',
        'under_warranty' => 'boolean',
    ];

    public const STAGES = [
        'new'            => 'New',
        'in_progress'    => 'In Progress',
        'awaiting_parts' => 'Awaiting Parts',
        'completed'      => 'Completed',
        'cancelled'      => 'Cancelled',
    ];

    public const PRIORITIES = [
        'low'    => 'Low',
        'normal' => 'Normal',
        'urgent' => 'Urgent',
    ];

    public function equipment()
    {
        return $this->belongsTo(FSMEquipment::class, 'equipment_id');
    }

    public function location()
    {
        return $this->belongsTo(FSMLocation::class, 'fsm_location_id');
    }

    public function template()
    {
        return $this->belongsTo(RepairOrderTemplate::class, 'template_id');
    }

    public function fsmOrder()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function reporter()
    {
        return $this->belongsTo(\App\Models\User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    /**
     * Compute under_warranty from the equipment's active warranty record.
     */
    public function computeUnderWarranty(): bool
    {
        if (!$this->equipment_id) {
            return false;
        }

        return EquipmentWarranty::where('equipment_id', $this->equipment_id)
            ->where('warranty_start', '<=', now()->toDateString())
            ->where('warranty_end', '>=', now()->toDateString())
            ->exists();
    }

    /**
     * Days the equipment was out of service (from date_reported to date_completed).
     */
    public function downtimeDays(): ?int
    {
        if (!$this->date_reported) {
            return null;
        }
        $end = $this->date_completed ?? now();
        return (int) $this->date_reported->diffInDays($end);
    }
}
