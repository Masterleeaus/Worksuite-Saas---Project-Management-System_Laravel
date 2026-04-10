<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Suppression extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_suppressions';
    protected $guarded = ['id'];

    protected $casts = [
        'suppressed_at' => 'datetime',
    ];
}