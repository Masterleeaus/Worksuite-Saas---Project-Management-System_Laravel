<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMTeam extends Model
{
    protected $table = 'fsm_teams';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'company_id' => 'integer',
    ];

    public function members()
    {
        return $this->belongsToMany(\App\Models\User::class, 'fsm_team_user', 'fsm_team_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'team_id');
    }
}
