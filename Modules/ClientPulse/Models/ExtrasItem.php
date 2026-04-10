<?php

namespace Modules\ClientPulse\Models;

use Illuminate\Database\Eloquent\Model;

class ExtrasItem extends Model
{
    protected $table = 'client_pulse_extras_items';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'active'     => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('sort_order')->orderBy('name');
    }
}
