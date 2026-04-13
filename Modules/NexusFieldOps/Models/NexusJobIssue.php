<?php

namespace Modules\NexusFieldOps\Models;

use Illuminate\Database\Eloquent\Model;

class NexusJobIssue extends Model
{
    protected $table = 'nexus_job_issues';

    public const TYPES = [
        'access_blocked',
        'customer_absent',
        'damage',
        'extra_work',
        'safety_risk',
        'equipment_missing',
    ];

    public const STATUSES = ['open', 'acknowledged', 'resolved'];

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'worker_id',
        'issue_type',
        'description',
        'status',
        'synced_offline',
        'acknowledged_at',
    ];

    protected $casts = [
        'company_id'      => 'integer',
        'fsm_order_id'    => 'integer',
        'worker_id'       => 'integer',
        'synced_offline'  => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(\Modules\FSMCore\Models\FSMOrder::class, 'fsm_order_id');
    }

    public function worker()
    {
        return $this->belongsTo(\App\Models\User::class, 'worker_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
