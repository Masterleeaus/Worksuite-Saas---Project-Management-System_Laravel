<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class Courier extends BaseModel
{
    use HasCompany;

    protected $table = 'tr_package_courier';
    protected $guarded = ['id'];

    public function packages()
    {
        return $this->hasMany(Package::class, 'courier_id');
    }
}
