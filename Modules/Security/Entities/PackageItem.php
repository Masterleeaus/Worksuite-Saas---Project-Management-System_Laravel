<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;

class PackageItem extends BaseModel
{
    protected $table = 'tr_package_items';
    protected $guarded = ['id'];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
