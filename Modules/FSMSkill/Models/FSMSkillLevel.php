<?php

namespace Modules\FSMSkill\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class FSMSkillLevel extends Model
{
    use HasCompany;

    protected $table = 'fsm_skill_levels';

    protected $fillable = [
        'company_id',
        'skill_id',
        'name',
        'progress',
        'default_level',
    ];

    protected $casts = [
        'company_id'    => 'integer',
        'skill_id'      => 'integer',
        'progress'      => 'integer',
        'default_level' => 'boolean',
    ];

    public function skill()
    {
        return $this->belongsTo(FSMSkill::class, 'skill_id');
    }

    public function employeeSkills()
    {
        return $this->hasMany(FSMEmployeeSkill::class, 'skill_level_id');
    }
}
