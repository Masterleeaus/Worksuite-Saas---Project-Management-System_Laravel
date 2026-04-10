<?php

namespace Modules\Security\Entities;

use App\Models\User;
use App\Models\BaseModel;
use App\Traits\HasCompany;
use App\Models\ModuleSetting;
use Modules\Units\Entities\Unit;
use Modules\AuditLog\app\Traits\ConditionalAuditable;

class InOutPermit extends BaseModel
{
    use HasCompany;
    use ConditionalAuditable;

    protected $table = 'tr_inout_permit';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_inout_permit';

    public static function addModuleSetting($company)
    {
        $roles = ['admin', 'employee', 'client'];
        ModuleSetting::createRoleSettingEntry(self::MODULE_NAME, $roles, $company);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedByBm()
    {
        return $this->belongsTo(User::class, 'approved_bm');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'inout_permit_id');
    }
}
