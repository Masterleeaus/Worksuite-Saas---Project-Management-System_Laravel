<?php

namespace Modules\Workflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\Traits\CompanyScoped;

class WorkflowSetting extends Model
{
    use CompanyScoped;
    protected $table = 'workflow_settings';

    protected $fillable = [
        'company_id', 'key', 'value', 'updated_by'
    ];

    protected $casts = [
        'value' => 'array',
    ];
}