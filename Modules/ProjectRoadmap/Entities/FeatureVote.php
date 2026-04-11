<?php

namespace Modules\ProjectRoadmap\Entities;

use App\Models\BaseModel;

class FeatureVote extends BaseModel
{
    protected $table = 'projectroadmap_feature_votes';

    protected $guarded = ['id'];

    public function roadmapItem()
    {
        return $this->belongsTo(RoadmapItem::class, 'roadmap_item_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
