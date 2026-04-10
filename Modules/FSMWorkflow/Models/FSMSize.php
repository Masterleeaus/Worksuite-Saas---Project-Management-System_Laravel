<?php

namespace Modules\FSMWorkflow\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a job size/scope classification tier.
 * Default tiers: XS / S / M / L / XL
 */
class FSMSize extends Model
{
    protected $table = 'fsm_sizes';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'description',
        'sequence',
        'active',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'sequence'   => 'integer',
        'active'     => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(\Modules\FSMCore\Models\FSMOrder::class, 'size_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
