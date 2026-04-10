<?php

namespace Modules\Aitools\Entities\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait CompanyScoped
{
    public static function bootCompanyScoped(): void
    {
        // If Worksuite has a global helper `company()` you can bind here.
        // We keep this trait conservative: no automatic scoping (avoid accidental lockouts).
    }

    public function scopeForCompany(Builder $query, ?int $companyId): Builder
    {
        return $query->where(function (Builder $q) use ($companyId) {
            $q->whereNull('company_id');
            if (!is_null($companyId)) {
                $q->orWhere('company_id', $companyId);
            }
        });
    }
}
