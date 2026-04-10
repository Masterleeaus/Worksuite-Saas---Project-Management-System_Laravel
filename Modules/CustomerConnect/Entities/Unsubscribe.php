<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Unsubscribe extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_unsubscribes';
    protected $guarded = ['id'];

    protected $casts = [
        'unsubscribed_at' => 'datetime',
    ];
}