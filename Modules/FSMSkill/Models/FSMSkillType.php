<?php

namespace Modules\FSMSkill\Models;

use Illuminate\Database\Eloquent\Model;

class FSMSkillType extends Model
{
    protected $table = 'fsm_skill_types';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'active'     => 'boolean',
    ];

    public function skills()
    {
        return $this->hasMany(FSMSkill::class, 'skill_type_id');
    }
}
