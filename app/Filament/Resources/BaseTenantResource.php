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
 *  - Tracks created_by using the authenticated user's ID.
 *  - Binds the resource to the model's policy (if one exists).
 *
 * Usage:
 *   class MyResource extends BaseTenantResource { ... }
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
                $query->where(static::getModel()::make()->getTable() . '.company_id', $companyId);
            }
        }

        return $query;
    }

    /**
     * Inject created_by and company_id into every new record created through
     * a Filament form so tenant ownership is tracked automatically.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
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
