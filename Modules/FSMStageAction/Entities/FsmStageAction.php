<?php

namespace Modules\FSMStageAction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\FSMCore\Entities\FsmStage;

class FsmStageAction extends Model
{
    protected $table = 'fsm_stage_actions';

    protected $fillable = [
        'company_id', 'name', 'stage_id', 'action_type',
        'email_template', 'sms_template',
        'set_field_name', 'set_field_value',
        'webhook_url', 'webhook_headers',
        'active', 'sequence',
    ];

    protected $casts = [
        'active'          => 'boolean',
        'webhook_headers' => 'array',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(FsmStage::class, 'stage_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(FsmStageActionLog::class, 'stage_action_id');
    }
}
