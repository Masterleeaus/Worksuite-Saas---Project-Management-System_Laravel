<?php

namespace Modules\FSMSkill\Models;

use Illuminate\Database\Eloquent\Model;

class FSMOrderSkillRequirement extends Model
{
    protected $table = 'fsm_order_skill_requirements';

    protected $fillable = [
        'fsm_order_id',
        'skill_id',
        'skill_level_id',
    ];

    protected $casts = [
        'fsm_order_id'   => 'integer',
        'skill_id'       => 'integer',
        'skill_level_id' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function skill()
    {
        return $this->belongsTo(FSMSkill::class, 'skill_id');
    }

    public function skillLevel()
    {
        return $this->belongsTo(FSMSkillLevel::class, 'skill_level_id');
    }
}
