<?php

namespace Modules\ProjectRoadmap\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class RoadmapItem extends BaseModel
{
    use HasCompany;

    protected $table = 'projectroadmap_items';

    protected $guarded = ['id'];

    protected $casts = [
        'is_public' => 'boolean',
        'votes'     => 'integer',
    ];

    const STATUSES = [
        'planned'     => 'Planned',
        'in_progress' => 'In Progress',
        'in_review'   => 'In Review',
        'launched'    => 'Launched',
        'cancelled'   => 'Cancelled',
    ];

    const STATUS_COLORS = [
        'planned'     => 'secondary',
        'in_progress' => 'primary',
        'in_review'   => 'warning',
        'launched'    => 'success',
        'cancelled'   => 'danger',
    ];

    public function featureVotes()
    {
        return $this->hasMany(FeatureVote::class, 'roadmap_item_id');
    }

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

    public function hasVotedBy(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->featureVotes()->where('user_id', $userId)->exists();
    }
}
