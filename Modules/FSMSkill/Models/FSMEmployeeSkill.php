<?php

namespace Modules\FSMSkill\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FSMEmployeeSkill extends Model
{
    protected $table = 'fsm_employee_skills';

    protected $fillable = [
        'company_id',
        'user_id',
        'skill_id',
        'skill_level_id',
        'expiry_date',
        'certificate_path',
        'notes',
    ];

    protected $casts = [
        'company_id'     => 'integer',
        'user_id'        => 'integer',
        'skill_id'       => 'integer',
        'skill_level_id' => 'integer',
        'expiry_date'    => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function skill()
    {
        return $this->belongsTo(FSMSkill::class, 'skill_id');
    }

    public function skillLevel()
    {
        return $this->belongsTo(FSMSkillLevel::class, 'skill_level_id');
    }

    /** Returns true if the certification has already expired. */
    public function isExpired(): bool
    {
        return $this->expiry_date !== null && $this->expiry_date->isPast();
    }

    /** Returns true if the certification expires within the configured warning window. */
    public function isExpiringSoon(): bool
    {
        if ($this->expiry_date === null) {
            return false;
        }
        $days = (int) config('fsmskill.expiry_warning_days', 30);
        return !$this->isExpired() && $this->expiry_date->lte(Carbon::now()->addDays($days));
    }
}
