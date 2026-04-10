<?php

namespace Modules\ZoneManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ZoneManagement\Traits\CompanyScoped;

class RoutePoint extends Model
{
    use HasFactory;
    use CompanyScoped;

    protected $table = 'route_points';

    protected $fillable = [
        'company_id',
        'user_id',
        'booking_id',
        'lat',
        'lng',
        'accuracy',
        'sequence',
        'recorded_at',
    ];

    protected $casts = [
        'lat'         => 'float',
        'lng'         => 'float',
        'accuracy'    => 'float',
        'sequence'    => 'integer',
        'recorded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
