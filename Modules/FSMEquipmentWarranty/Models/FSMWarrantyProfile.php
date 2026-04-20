<?php

namespace Modules\FSMEquipmentWarranty\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FSMWarrantyProfile extends Model
{
    protected $table = 'fsm_warranty_profiles';

    protected $fillable = [
        'company_id',
        'name',
        'equipment_category',
        'warranty_duration',
        'warranty_type',
        'notes',
    ];

    protected $casts = [
        'company_id'        => 'integer',
        'warranty_duration' => 'integer',
    ];

    const TYPES = [
        'day'   => 'Days',
        'week'  => 'Weeks',
        'month' => 'Months',
        'year'  => 'Years',
    ];

    public function computeEndDate(Carbon $startDate): Carbon
    {
        return match ($this->warranty_type) {
            'week'  => $startDate->copy()->addWeeks($this->warranty_duration),
            'month' => $startDate->copy()->addMonths($this->warranty_duration),
            'year'  => $startDate->copy()->addYears($this->warranty_duration),
            default => $startDate->copy()->addDays($this->warranty_duration),
        };
    }
}
