<?php

namespace Modules\PromotionManagement\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait CompanyScoped
{
    protected static function bootCompanyScoped(): void
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'company_id')) {
                $companyId = static::resolveCompanyId();
                if (empty($model->company_id) && !empty($companyId)) {
                    $model->company_id = $companyId;
                }
            }
        });

        static::addGlobalScope('company_id', function (Builder $builder) {
            try {
                $model = $builder->getModel();
                $companyId = static::resolveCompanyId();

                if (!$companyId) {
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

    private static function resolveCompanyId(): ?int
    {
        if (function_exists('user')) {
            $sessionUser = user();
            if ($sessionUser && isset($sessionUser->company_id)) {
                return (int) $sessionUser->company_id;
            }
        }

        $authUser = Auth::user();
        if (!$authUser) {
            return null;
        }

        if (isset($authUser->company_id)) {
            return (int) $authUser->company_id;
        }

        if (isset($authUser->user) && isset($authUser->user->company_id)) {
            return (int) $authUser->user->company_id;
        }

        return null;
    }
}
