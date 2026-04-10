<?php

namespace Modules\FSMSkill\Models;

use Illuminate\Database\Eloquent\Model;

class FSMTemplateSkillRequirement extends Model
{
    protected $table = 'fsm_template_skill_requirements';

    protected $fillable = [
        'fsm_template_id',
        'skill_id',
        'skill_level_id',
    ];

    protected $casts = [
        'fsm_template_id' => 'integer',
        'skill_id'        => 'integer',
        'skill_level_id'  => 'integer',
    ];

    public function template()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMTemplate::class, 'fsm_template_id');
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
