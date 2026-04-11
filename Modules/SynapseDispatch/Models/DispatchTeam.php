<?php

namespace Modules\SynapseDispatch\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchTeam extends Model
{
    protected $table = 'dispatch_teams';

    protected $fillable = [
        'code',
        'name',
        'description',
        'planner_config',
    ];

    protected $casts = [
        'planner_config' => 'array',
    ];

    public function workers()
    {
        return $this->hasMany(DispatchWorker::class, 'team_id');
    }

    public function jobs()
    {
        return $this->hasMany(DispatchJob::class, 'team_id');
    }
}
