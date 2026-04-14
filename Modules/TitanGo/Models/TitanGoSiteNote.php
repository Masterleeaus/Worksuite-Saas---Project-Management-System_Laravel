<?php

namespace Modules\TitanGo\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class TitanGoSiteNote extends Model
{
    use HasCompany;

    protected $table = 'titan_go_site_notes';

    const TYPES = [
        'entry',
        'access_code',
        'parking',
        'equipment',
        'preference',
        'hazard',
        'repeat',
    ];

    protected $fillable = [
        'company_id',
        'location_id',
        'type',
        'content',
    ];

    protected $casts = [
        'company_id'  => 'integer',
        'location_id' => 'integer',
    ];
}
