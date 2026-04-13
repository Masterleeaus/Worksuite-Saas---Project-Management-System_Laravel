<?php

namespace Modules\TitanGo\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class TitanGoWorkerStatus extends Model
{
    use HasCompany;

    protected $table = 'titan_go_worker_statuses';

    const STATUSES = [
        'arrived',
        'running_late',
        'access_issue',
        'need_supplies',
        'job_complete',
        'report_issue',
    ];

    protected $fillable = [
        'company_id',
        'worker_id',
        'visit_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'worker_id'  => 'integer',
        'visit_id'   => 'integer',
    ];
}
