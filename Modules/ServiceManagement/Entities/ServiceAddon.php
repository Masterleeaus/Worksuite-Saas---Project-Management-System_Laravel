<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAddon extends Model
{
    use SoftDeletes;

    protected $table = 'service_addons';

    protected $fillable = [
        'name',
        'price',
        'duration_extra',
        'service_id',
        'is_active',
    ];

    protected $casts = [
        'price'          => 'float',
        'duration_extra' => 'integer',
        'is_active'      => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id')->withoutGlobalScopes();
    }
}
