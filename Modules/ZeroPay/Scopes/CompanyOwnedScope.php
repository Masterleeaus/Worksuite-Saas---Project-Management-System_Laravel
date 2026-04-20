<?php

namespace Modules\ZeroPay\Scopes;

use Illuminate\Database\Eloquent\Builder;

class CompanyOwnedScope
{
    public static function apply(Builder $query, ?int $companyId): Builder
    {
        if (!$companyId) {
            return $query;
        }

        return $query->where($query->getModel()->getTable() . '.company_id', $companyId);
    }
}
