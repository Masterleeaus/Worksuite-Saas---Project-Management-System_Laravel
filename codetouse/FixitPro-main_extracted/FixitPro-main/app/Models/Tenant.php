<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_tenants';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'business_name',
        'owner_name',
        'email',
        'phone',
        'logo',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'plan_type',
        'status',
        'trial_ends_at',
        'stripe_customer_id',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'ID');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'tenant_id', 'ID')
            ->where('status', 'active')
            ->latest();
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'tenant_id', 'ID');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function getPlanBadgeColorAttribute(): string
    {
        return match($this->plan_type) {
            'starter' => 'gray',
            'professional' => 'blue',
            'enterprise' => 'purple',
            default => 'gray'
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'emerald',
            'trial' => 'amber',
            'inactive' => 'gray',
            'suspended' => 'red',
            default => 'gray'
        };
    }
}
