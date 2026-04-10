<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $table = 'tbl_audit_logs';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'admin_name',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    /**
     * Log an action
     */
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $modelName = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        $admin = Auth::guard('admin')->user();

        return static::create([
            'admin_id' => $admin?->id,
            'admin_name' => $admin?->name ?? 'System',
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'model_name' => $modelName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
            'created_at' => now(),
        ]);
    }

    public function getActionBadgeColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'emerald',
            'updated' => 'blue',
            'deleted' => 'red',
            'login' => 'indigo',
            'logout' => 'gray',
            'impersonated' => 'amber',
            'suspended' => 'red',
            'activated' => 'emerald',
            default => 'gray'
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'impersonated' => 'fa-user-secret',
            'suspended' => 'fa-ban',
            'activated' => 'fa-check-circle',
            default => 'fa-circle'
        };
    }

    public function scopeRecent($query, int $limit = 50)
    {
        return $query->latest('created_at')->take($limit);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }
}
