<?php

namespace Modules\ZoneManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ZoneManagement\Traits\CompanyScoped;

class ZoneCheckIn extends Model
{
    use HasFactory;
    use CompanyScoped;

    protected $table = 'zone_check_ins';

    protected $fillable = [
        'company_id',
        'booking_id',
        'user_id',
        'check_in_lat',
        'check_in_lng',
        'check_in_accuracy',
        'checked_in_at',
        'check_out_lat',
        'check_out_lng',
        'check_out_accuracy',
        'checked_out_at',
        'is_verified',
        'within_geofence',
        'notes',
    ];

    protected $casts = [
        'check_in_lat'      => 'float',
        'check_in_lng'      => 'float',
        'check_in_accuracy' => 'float',
        'checked_in_at'     => 'datetime',
        'check_out_lat'     => 'float',
        'check_out_lng'     => 'float',
        'check_out_accuracy'=> 'float',
        'checked_out_at'    => 'datetime',
        'is_verified'       => 'boolean',
        'within_geofence'   => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
