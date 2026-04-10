<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

class SavedFilter extends Model
{
    use CompanyScoped;
    protected $table    = 'customerconnect_saved_filters';
    protected $guarded  = [];
    protected $casts    = [
        'criteria'   => 'array',
        'is_default' => 'boolean',
    ];
}