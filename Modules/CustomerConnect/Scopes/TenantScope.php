<?php

namespace Modules\CustomerConnect\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Enforces tenant isolation for CustomerConnect records.
 *
 * MVP rule: company_id and user_id are present and scoped.
 * Current tenant rule (per Worksuite/Titan): company_id == user_id.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Only scope in web/interactive contexts where an authenticated user exists.
        // Background jobs should carry tenant ids on the records they load.
        if (!function_exists('auth') || !auth()->check()) {
            return;
        }

        $tenantId = (int) auth()->id();

        // Allow legacy rows with null company_id/user_id to be visible only to super admins if needed.
        // Default is strict scoping.
        $builder->where($model->getTable().'.company_id', $tenantId)
            ->where($model->getTable().'.user_id', $tenantId);
    }
}
