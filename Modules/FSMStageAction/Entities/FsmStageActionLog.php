<?php

namespace Modules\FSMStageAction\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\FSMCore\Entities\FsmOrder;

class FsmStageActionLog extends Model
{
    public $timestamps = false;

    protected $table = 'fsm_stage_action_logs';

    protected $fillable = ['stage_action_id', 'fsm_order_id', 'status', 'message', 'ran_at'];

    protected $casts = ['ran_at' => 'datetime'];

    public function action(): BelongsTo
    {
        return $this->belongsTo(FsmStageAction::class, 'stage_action_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(FsmOrder::class, 'fsm_order_id');
    }
}
