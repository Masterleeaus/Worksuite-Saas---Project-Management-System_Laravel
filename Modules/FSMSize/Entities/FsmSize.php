<?php

namespace Modules\FSMSize\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FsmSize extends Model
{
    protected $table = 'fsm_sizes';

    protected $fillable = [
        'company_id', 'name', 'unit_of_measure', 'type_id',
        'parent_id', 'is_order_size', 'active',
    ];

    protected $casts = [
        'is_order_size' => 'boolean',
        'active'        => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(FsmSize::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(FsmSize::class, 'parent_id');
    }
}
