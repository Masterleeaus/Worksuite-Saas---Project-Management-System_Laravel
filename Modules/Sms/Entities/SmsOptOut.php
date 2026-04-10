<?php

namespace Modules\Sms\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;

class SmsOptOut extends BaseModel
{
    use HasCompany;

    protected $table = 'sms_opt_outs';

    protected $guarded = ['id'];

    protected $casts = [
        'opted_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Check if a phone number is opted out for a given company.
     */
    public static function isOptedOut(string $phoneNumber, ?int $companyId = null): bool
    {
        return static::where('phone_number', $phoneNumber)
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->exists();
    }
}
