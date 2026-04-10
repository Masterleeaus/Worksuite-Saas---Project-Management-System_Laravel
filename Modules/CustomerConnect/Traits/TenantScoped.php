<?php

namespace Modules\CustomerConnect\Traits;

use Modules\CustomerConnect\Scopes\TenantScope;

/**
 * Adds automatic tenant scoping + auto-fill for company_id/user_id.
 *
 * - company_id and user_id are enforced on queries (when auth() exists)
 * - on create, company_id/user_id default to auth()->id() if missing
 */
trait TenantScoped
{
    protected static function bootTenantScoped(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (!function_exists('auth') || !auth()->check()) {
                return;
            }

            $tenantId = (int) auth()->id();

            if (property_exists($model, 'company_id') || array_key_exists('company_id', $model->getAttributes())) {
                $model->company_id = $model->company_id ?? $tenantId;
            } else {
                // Even if not an explicit property, Eloquent will allow dynamic attributes.
                $model->company_id = $model->company_id ?? $tenantId;
            }

            $model->user_id = $model->user_id ?? $tenantId;
        });
    }
}
