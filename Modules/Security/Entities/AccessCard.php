<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use App\Models\ModuleSetting;
use Modules\Units\Entities\Unit;
use Modules\AuditLog\app\Traits\ConditionalAuditable;

class AccessCard extends BaseModel
{
    use HasCompany;
    use ConditionalAuditable;

    protected $table = 'tr_access_card';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_access_card';

    public static function addModuleSetting($company)
    {
        $roles = ['admin', 'employee', 'client'];
        ModuleSetting::createRoleSettingEntry(self::MODULE_NAME, $roles, $company);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function items()
    {
        return $this->hasMany(CardItems::class, 'card_id');
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'access_card_id');
    }
}
