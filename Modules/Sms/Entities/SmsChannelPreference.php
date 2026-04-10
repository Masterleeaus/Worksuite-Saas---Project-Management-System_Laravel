<?php

namespace Modules\Sms\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class SmsChannelPreference extends BaseModel
{
    use HasCompany;

    protected $table = 'sms_channel_preferences';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the preferred channel for a user ('sms' or 'whatsapp').
     */
    public static function forUser(int $userId, ?int $companyId = null): string
    {
        $pref = static::where('user_id', $userId)
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->first();

        return $pref ? $pref->channel : 'sms';
    }
}
