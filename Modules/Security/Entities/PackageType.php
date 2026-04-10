<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class PackageType extends BaseModel
{
    use HasCompany;

    protected $table = 'tr_package_type';
    protected $guarded = ['id'];

    public function packages()
    {
        return $this->hasMany(Package::class, 'type_id');
    }
}
