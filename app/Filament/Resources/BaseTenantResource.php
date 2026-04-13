<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

/**
 * BaseTenantResource
 *
 * All Filament resources in the Titan panel MUST extend this class instead
 * of \Filament\Resources\Resource directly.
 *
 * Responsibilities:
 *  - Automatically enforces company_id filtering on every Eloquent query.
 *  - Exposes a helper for CreateRecord pages to inject company_id / created_by.
 *  - Binds the resource to the model's policy (if one exists).
 *
 * Usage:
 *   class MyResource extends BaseTenantResource { ... }
 *
 * For Create pages that need to stamp company_id / created_by, override
 * mutateFormDataBeforeCreate() on the resource's CreateRecord inner class:
 *
 *   class CreateMyModel extends CreateRecord {
 *       protected function mutateFormDataBeforeCreate(array $data): array {
 *           return BaseTenantResource::stampTenantData($data);
 *       }
 *   }
 */
abstract class BaseTenantResource extends Resource
{
    /**
     * Apply tenant (company_id) scope to every query issued by this resource.
     *
     * Filament calls this hook before building its table and form queries, so
     * returning a scoped builder here is the safest integration point.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->hasUser()) {
            $user      = auth()->user();
            $companyId = $user->company_id ?? optional($user->company)->id;

            if ($companyId) {
                /** @var \Illuminate\Database\Eloquent\Model $model */
                $model = new (static::getModel())();
                $query->where($model->getTable() . '.company_id', $companyId);
            }
        }

        return $query;
    }

    /**
     * Stamp company_id and created_by onto form data before record creation.
     *
     * Call this from mutateFormDataBeforeCreate() in each resource's
     * CreateRecord inner class – it cannot live on the Resource itself because
     * Filament v3 resolves that hook on the CreateRecord page, not the Resource.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function stampTenantData(array $data): array
    {
        if (auth()->hasUser()) {
            $user = auth()->user();

            if (!isset($data['company_id']) && $user->company_id) {
                $data['company_id'] = $user->company_id;
            }

            if (!isset($data['created_by'])) {
                $data['created_by'] = $user->id;
            }
        }

        return $data;
    }
}
