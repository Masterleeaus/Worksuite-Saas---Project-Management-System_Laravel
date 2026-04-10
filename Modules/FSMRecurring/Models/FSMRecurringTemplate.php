<?php

namespace Modules\FSMRecurring\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * FSMRecurringTemplate – named preset for recurring order configurations.
 * Ported from Odoo fsm.recurring.template.
 */
class FSMRecurringTemplate extends Model
{
    protected $table = 'fsm_recurring_templates';

    protected $fillable = [
        'company_id', 'name', 'active', 'description',
        'frequency_set_id', 'max_orders', 'fsm_template_id',
    ];

    protected $casts = [
        'active'     => 'boolean',
        'max_orders' => 'integer',
    ];

    public function frequencySet()
    {
        return $this->belongsTo(FSMFrequencySet::class, 'frequency_set_id');
    }

    public function fsmTemplate()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMTemplate::class, 'fsm_template_id');
    }
}
