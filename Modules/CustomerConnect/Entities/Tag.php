<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Tag extends Model
{
    use CompanyScoped;
    protected $table = 'customerconnect_tags';
    protected $guarded = [];
}