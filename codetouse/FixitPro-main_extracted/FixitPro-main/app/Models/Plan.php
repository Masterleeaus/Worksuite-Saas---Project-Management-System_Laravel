<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'tbl_plans';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'worker_limit',
        'client_limit',
        'job_limit',
        'features',
        'is_popular',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_name', 'name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('price');
    }

    public function getFormattedPriceAttribute(): string
    {
        $price = number_format($this->price, 2);
        $cycle = $this->billing_cycle === 'monthly' ? '/mo' : '/yr';
        return '$' . $price . $cycle;
    }

    public function getBadgeColorAttribute(): string
    {
        return match(true) {
            $this->price >= 200 => 'purple',
            $this->price >= 50 => 'blue',
            default => 'gray'
        };
    }
}
