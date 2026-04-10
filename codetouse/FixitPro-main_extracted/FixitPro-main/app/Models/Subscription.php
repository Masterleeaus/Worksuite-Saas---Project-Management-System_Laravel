<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'tbl_subscriptions';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'tenant_id',
        'stripe_subscription_id',
        'stripe_price_id',
        'plan_name',
        'monthly_amount',
        'status',
        'billing_cycle',
        'current_period_start',
        'current_period_end',
        'canceled_at',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'ID');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getYearlyAmountAttribute(): float
    {
        return $this->billing_cycle === 'yearly' 
            ? $this->monthly_amount 
            : $this->monthly_amount * 12;
    }
}
