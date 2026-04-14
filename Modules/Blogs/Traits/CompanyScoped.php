<?php

namespace Modules\Blogs\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait CompanyScoped
{
    protected static function bootCompanyScoped(): void
    {
        static::creating(function ($model) {
            if (property_exists($model, 'company_id') || Schema::hasColumn($model->getTable(), 'company_id')) {
                $companyId = self::resolveTenantCompanyId();

                if (empty($model->company_id) && !empty($companyId)) {
                    $model->company_id = $companyId;
                }
            }
        });

        static::addGlobalScope('company_id', function (Builder $builder) {
            try {
                $model = $builder->getModel();
                $companyId = self::resolveTenantCompanyId();

                if (empty($companyId)) {
                    return;
                }

                if (Schema::hasColumn($model->getTable(), 'company_id')) {
                    $builder->where($model->getTable().'.company_id', $companyId);
                }
            } catch (\Throwable $e) {
                // fail-open to avoid breaking boot if schema not ready during migrations
            }
        });
    }

    private static function resolveTenantCompanyId(): ?int
    {
        try {
            if (function_exists('user') && user()) {
                return (int) user()->company_id;
            }

            if (Auth::check()) {
                $authUser = Auth::user();

                if (isset($authUser->company_id)) {
                    return (int) $authUser->company_id;
                }

                if (isset($authUser->user) && isset($authUser->user->company_id)) {
                    return (int) $authUser->user->company_id;
                }
            }

            if (request()->has('company_id') && is_numeric(request('company_id'))) {
                return (int) request('company_id');
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}
