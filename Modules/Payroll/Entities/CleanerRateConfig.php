<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Models\Company;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CleanerRateConfig extends BaseModel
{
    use HasCompany;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'night_rate_cutoff' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the effective rate config for a given user, optionally per contract.
     * Falls back: per-user per-contract → per-user → global default.
     */
    public static function effectiveFor(int $userId, ?string $contractRef = null): ?self
    {
        $companyId = company() ? company()->id : null;

        // 1. Per-user + per-contract
        if ($contractRef) {
            $config = self::where('company_id', $companyId)
                ->where('user_id', $userId)
                ->where('contract_ref', $contractRef)
                ->where('is_active', true)
                ->first();
            if ($config) {
                return $config;
            }
        }

        // 2. Per-user (no contract)
        $config = self::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->whereNull('contract_ref')
            ->where('is_active', true)
            ->first();
        if ($config) {
            return $config;
        }

        // 3. Global default
        return self::where('company_id', $companyId)
            ->whereNull('user_id')
            ->whereNull('contract_ref')
            ->where('is_active', true)
            ->first();
    }
}
