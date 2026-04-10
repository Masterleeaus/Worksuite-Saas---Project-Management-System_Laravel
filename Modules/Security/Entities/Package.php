<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use App\Models\ModuleSetting;
use Modules\Units\Entities\Unit;
use Modules\AuditLog\app\Traits\ConditionalAuditable;

class Package extends BaseModel
{
    use HasCompany;
    use ConditionalAuditable;

    protected $table = 'tr_package';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_package';

    public static function addModuleSetting($company)
    {
        $roles = ['admin', 'employee'];
        ModuleSetting::createRoleSettingEntry(self::MODULE_NAME, $roles, $company);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function type()
    {
        return $this->belongsTo(PackageType::class, 'type_id');
    }

    public function items()
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'package_id');
    }
}
