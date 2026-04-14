<?php

namespace Modules\BusinessSettingsModule\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait CompanyScoped
{
    protected static function resolveAuthenticatedCompanyId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        $authUser = Auth::user();

        if (isset($authUser->company_id) && !empty($authUser->company_id)) {
            return (int) $authUser->company_id;
        }

        if (isset($authUser->user) && isset($authUser->user->company_id) && !empty($authUser->user->company_id)) {
            return (int) $authUser->user->company_id;
        }

        if (function_exists('user')) {
            $resolvedUser = user();
            if ($resolvedUser && isset($resolvedUser->company_id) && !empty($resolvedUser->company_id)) {
                return (int) $resolvedUser->company_id;
            }
        }

        return null;
    }

    protected static function bootCompanyScoped(): void
    {
        static::creating(function ($model) {
            $companyId = static::resolveAuthenticatedCompanyId();
            if (property_exists($model, 'company_id') || Schema::hasColumn($model->getTable(), 'company_id')) {
                if (empty($model->company_id) && !empty($companyId)) {
                    $model->company_id = $companyId;
                }
            }
        });

        static::addGlobalScope('company_id', function (Builder $builder) {
            try {
                $model = $builder->getModel();
                $companyId = static::resolveAuthenticatedCompanyId();
                if (empty($companyId)) {
                    return;
                }
                if (Schema::hasColumn($model->getTable(), 'company_id')) {
                    $builder->where($model->getTable() . '.company_id', $companyId);
                }
            } catch (\Throwable $e) {
                // fail-open to avoid breaking boot if schema not ready during migrations
            }
        });
    }
}
