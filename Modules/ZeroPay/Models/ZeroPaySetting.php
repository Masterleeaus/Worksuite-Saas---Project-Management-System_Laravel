<?php

namespace Modules\ZeroPay\Models;

use App\Traits\HasCompany;

class ZeroPaySetting extends \App\Models\BaseModel
{
    use HasCompany;

    protected $table = 'zeropay_settings';

    protected $guarded = ['id'];

    protected $casts = [
        'settings' => 'array',
    ];

    public static function forCompany(?int $companyId): ?self
    {
        if (!$companyId) {
            return null;
        }

        return static::query()->where('company_id', $companyId)->first();
    }

    public static function getCompanySettings(?int $companyId): array
    {
        $settings = static::forCompany($companyId)?->settings;

        return is_array($settings) ? $settings : [];
    }
}
