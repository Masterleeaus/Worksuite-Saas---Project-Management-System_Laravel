<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Modules\Units\Entities\Unit;

class SecurityRecord extends BaseModel
{
    use HasCompany;

    protected $table = 'security_records';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_record';

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function relatedAccessCard()
    {
        return $this->belongsTo(AccessCard::class, 'related_access_card_id');
    }

    public function relatedWorkPermit()
    {
        return $this->belongsTo(WorkPermit::class, 'related_work_permit_id');
    }

    public function relatedPackage()
    {
        return $this->belongsTo(Package::class, 'related_package_id');
    }
}
