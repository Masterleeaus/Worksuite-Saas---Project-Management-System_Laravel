<?php

namespace Modules\ProjectRoadmap\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class RoadmapMilestone extends BaseModel
{
    use HasCompany;

    protected $table = 'projectroadmap_milestones';

    protected $guarded = ['id'];

    protected $casts = [
        'target_date'    => 'date',
        'completed_date' => 'date',
    ];

    const STATUSES = [
        'planned'     => 'Planned',
        'in_progress' => 'In Progress',
        'completed'   => 'Completed',
        'cancelled'   => 'Cancelled',
    ];

    const STATUS_COLORS = [
        'planned'     => 'secondary',
        'in_progress' => 'primary',
        'completed'   => 'success',
        'cancelled'   => 'danger',
    ];

    public function addedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'added_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }
}
