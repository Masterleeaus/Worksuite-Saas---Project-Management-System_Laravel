<?php

namespace Modules\RestAPI\Entities;

use App\Models\ModuleSetting;
use Illuminate\Database\Eloquent\Model;
use Modules\RestAPI\Traits\CompanyScoped;

class RestAPISetting extends Model
{
    use CompanyScoped;
    // region Properties

    protected $table = 'rest_api_settings';

    const MODULE_NAME = 'restapi';

    protected $default = ['id'];

    public static function addModuleSetting($company)
    {
        $roles = ['admin'];

        ModuleSetting::createRoleSettingEntry(self::MODULE_NAME, $roles, $company);
    }
}