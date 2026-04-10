<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePricingRule extends Model
{
    use SoftDeletes;

    protected $table = 'service_pricing_rules';

    protected $fillable = [
        'service_id',
        'zone_id',
        'label',
        'base_price_override',
        'per_bedroom_price',
        'per_bathroom_price',
        'min_price',
        'is_active',
    ];

    protected $casts = [
        'base_price_override' => 'float',
        'per_bedroom_price'   => 'float',
        'per_bathroom_price'  => 'float',
        'min_price'           => 'float',
        'is_active'           => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id')->withoutGlobalScopes();
    }

    public function zone()
    {
        if (class_exists(\Modules\ZoneManagement\Entities\Zone::class)) {
            return $this->belongsTo(\Modules\ZoneManagement\Entities\Zone::class, 'zone_id', 'id');
        }
        return null;
    }

    /**
     * Calculate price based on number of bedrooms and bathrooms.
     */
    public function calculatePrice(int $bedrooms = 0, int $bathrooms = 0): float
    {
        $base       = $this->base_price_override ?? 0;
        $roomsTotal = ($this->per_bedroom_price ?? 0) * $bedrooms
                    + ($this->per_bathroom_price ?? 0) * $bathrooms;
        $total = $base + $roomsTotal;

        if ($this->min_price && $total < $this->min_price) {
            $total = $this->min_price;
        }

        return round($total, 2);
    }
}
