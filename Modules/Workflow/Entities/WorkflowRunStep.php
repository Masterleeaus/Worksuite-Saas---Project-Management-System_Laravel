<?php

namespace Modules\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\Traits\CompanyScoped;

class WorkflowRunStep extends Model
{
    use CompanyScoped;
    protected $table = 'workflow_run_steps';
    protected $guarded = [];
    protected $casts = [
        'config' => 'array',
    ];
}