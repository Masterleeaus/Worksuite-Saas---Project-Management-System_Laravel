<?php

namespace Modules\FSMWorkflow\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMStage;

/**
 * Represents one automated action that fires when an FSM Order
 * transitions to the associated stage.
 *
 * action_type values:
 *   send_sms        – Send an SMS to the order's assigned worker/client
 *   send_email      – Send an email
 *   create_activity – Create a new FSMActivity on the order
 *   create_invoice  – Create a draft FSMSales invoice for the order
 *   webhook         – POST to webhook_url with order data as JSON
 *   custom          – Reserved for custom PHP logic via override
 */
class FSMStageAction extends Model
{
    protected $table = 'fsm_stage_actions';

    public const ACTION_TYPES = [
        'send_sms'        => 'Send SMS',
        'send_email'      => 'Send Email',
        'create_activity' => 'Create Activity',
        'create_invoice'  => 'Create Invoice',
        'webhook'         => 'Webhook',
        'custom'          => 'Custom',
    ];

    protected $fillable = [
        'company_id',
        'stage_id',
        'name',
        'action_type',
        'template_id',
        'activity_type_id',
        'webhook_url',
        'condition',
        'custom_payload',
        'sequence',
        'active',
    ];

    protected $casts = [
        'company_id'       => 'integer',
        'stage_id'         => 'integer',
        'template_id'      => 'integer',
        'activity_type_id' => 'integer',
        'sequence'         => 'integer',
        'active'           => 'boolean',
    ];

    public function stage()
    {
        return $this->belongsTo(FSMStage::class, 'stage_id');
    }

    public function activityType()
    {
        if (!class_exists(\Modules\FSMActivity\Models\FSMActivityType::class)) {
            return null;
        }
        return $this->belongsTo(\Modules\FSMActivity\Models\FSMActivityType::class, 'activity_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForStage($query, int $stageId)
    {
        return $query->where('stage_id', $stageId);
    }
}
