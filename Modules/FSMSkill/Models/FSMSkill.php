<?php

namespace Modules\FSMSkill\Models;

use Illuminate\Database\Eloquent\Model;

class FSMSkill extends Model
{
    protected $table = 'fsm_skills';

    protected $fillable = [
        'company_id',
        'skill_type_id',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'company_id'    => 'integer',
        'skill_type_id' => 'integer',
        'active'        => 'boolean',
    ];

    public function skillType()
    {
        return $this->belongsTo(FSMSkillType::class, 'skill_type_id');
    }

    public function levels()
    {
        return $this->hasMany(FSMSkillLevel::class, 'skill_id');
    }

    public function employeeSkills()
    {
        return $this->hasMany(FSMEmployeeSkill::class, 'skill_id');
    }

    public function orderRequirements()
    {
        return $this->hasMany(FSMOrderSkillRequirement::class, 'skill_id');
    }

    public function templateRequirements()
    {
        return $this->hasMany(FSMTemplateSkillRequirement::class, 'skill_id');
    }
}
