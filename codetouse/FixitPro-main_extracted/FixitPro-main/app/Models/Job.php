<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_jobs';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'customer_name',
        'customer_phone',
        'customer_email',
        'service_address',
        'job_type',
        'status',
        'priority',
        'estimated_cost',
        'final_cost',
        'scheduled_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'ID');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'amber',
            'scheduled' => 'blue',
            'in_progress' => 'indigo',
            'completed' => 'emerald',
            'canceled' => 'red',
            default => 'gray'
        };
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'amber',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    public function getJobTypeLabelAttribute(): string
    {
        return ucfirst($this->job_type);
    }
}
