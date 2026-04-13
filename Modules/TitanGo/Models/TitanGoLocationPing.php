<?php

namespace Modules\TitanGo\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class TitanGoLocationPing extends Model
{
    use HasCompany;

    protected $table = 'titan_go_location_pings';

    protected $fillable = [
        'company_id',
        'worker_id',
        'visit_id',
        'latitude',
        'longitude',
        'accuracy',
        'tracking_mode',
    ];

    protected $casts = [
        'company_id'    => 'integer',
        'worker_id'     => 'integer',
        'visit_id'      => 'integer',
        'latitude'      => 'float',
        'longitude'     => 'float',
        'accuracy'      => 'float',
    ];
}
