<?php

namespace Modules\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\Traits\CompanyScoped;

class WorkflowRun extends Model
{
    use CompanyScoped;
    protected $table = 'workflow_runs';
    protected $guarded = [];
    protected $casts = [
        'event_payload' => 'array',
    ];
}