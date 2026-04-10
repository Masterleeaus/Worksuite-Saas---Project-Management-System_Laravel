<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use App\Models\ModuleSetting;
use Modules\Units\Entities\Unit;
use App\Traits\CustomFieldsTrait;
use Modules\AuditLog\app\Traits\ConditionalAuditable;

class Parking extends BaseModel
{
    use CustomFieldsTrait;
    use HasCompany;
    use ConditionalAuditable;

    protected $table = 'tenan_parkir';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_parking';

    public static function addModuleSetting($company)
    {
        $roles = ['admin', 'employee'];
        ModuleSetting::createRoleSettingEntry(self::MODULE_NAME, $roles, $company);
    }

    public function items()
    {
        return $this->hasMany(ParkingItem::class, 'parking_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'parking_id');
    }
}
