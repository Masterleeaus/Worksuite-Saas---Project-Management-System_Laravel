<?php

namespace Modules\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\Traits\CompanyScoped;

class Workflow extends Model
{
    use CompanyScoped;
    protected $table = 'workflows';
    protected $guarded = [];
    protected $casts = [
        'workflow_data' => 'array',
    ];
}