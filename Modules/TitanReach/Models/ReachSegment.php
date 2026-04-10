<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;

class ReachSegment extends Model
{
    protected $table = 'reach_segments';

    protected $fillable = [
        'company_id', 'name', 'description', 'filters',
    ];

    protected $casts = [
        'filters' => 'array',
    ];
}
