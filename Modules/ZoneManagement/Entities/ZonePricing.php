<?php

namespace Modules\ZoneManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ZoneManagement\Traits\CompanyScoped;

class ZonePricing extends Model
{
    use HasFactory;
    use CompanyScoped;

    protected $table = 'zone_pricing';

    protected $fillable = [
        'zone_id',
        'service_id',
        'price_modifier',
        'company_id',
    ];

    protected $casts = [
        'price_modifier' => 'float',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
