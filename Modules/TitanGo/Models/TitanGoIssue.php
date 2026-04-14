<?php

namespace Modules\TitanGo\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class TitanGoIssue extends Model
{
    use HasCompany;

    protected $table = 'titan_go_issues';

    const TYPES = [
        'access_blocked',
        'customer_absent',
        'damage',
        'extra_work',
        'safety_risk',
        'equipment_missing',
    ];

    protected $fillable = [
        'company_id',
        'worker_id',
        'visit_id',
        'type',
        'description',
        'status',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'worker_id'  => 'integer',
        'visit_id'   => 'integer',
    ];
}
