<?php

namespace Modules\CyberSecurity\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class BreachReport extends BaseModel
{
    use HasFactory, HasCompany;

    protected $guarded = ['id'];

    protected $casts = [
        'breach_detected_at' => 'datetime',
        'notification_deadline' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getSeverityBadgeAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'danger',
            'high'     => 'warning',
            'medium'   => 'info',
            default    => 'secondary',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'resolved'     => 'success',
            'notified'     => 'info',
            'investigating' => 'warning',
            default        => 'danger',
        };
    }
}
