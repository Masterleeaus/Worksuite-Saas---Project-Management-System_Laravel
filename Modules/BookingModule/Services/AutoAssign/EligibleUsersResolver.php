<?php

namespace Modules\BookingModule\Services\AutoAssign;

use Illuminate\Support\Facades\DB;

class EligibleUsersResolver
{
    /**
     * Returns an array of user ids that are eligible to be assigned.
     */
    public function eligibleUserIds(?int $workspace = null, ?int $createdBy = null): array
    {
        $userModel = config('auth.providers.users.model', \App\Models\User::class);
        if (!class_exists($userModel)) {
            // Fallback for older Worksuite builds
            $userModel = class_exists(\App\User::class) ? \App\User::class : null;
        }

        if (!$userModel) {
            return [];
        }

        $query = $userModel::query();

        // If the app uses workspace scoping in users table, we do not assume schema.
        // We only scope by creatorId/workspace through permission tables where present.

        // Require permission if the Worksuite permission tables exist.
        $permission = config('bookingmodule::auto_assign.eligible_permission', 'appointment.assign');
        if (config('bookingmodule::auto_assign.require_permission', true) && $permission) {
            // Common Worksuite pattern: permission_user table or model_has_permissions.
            if (DB::getSchemaBuilder()->hasTable('permission_user')) {
                $query->whereIn('id', function ($sub) use ($permission) {
                    $sub->select('user_id')
                        ->from('permission_user')
                        ->where('permission_id', function ($p) use ($permission) {
                            $p->select('id')->from('permissions')->where('name', $permission)->limit(1);
                        });
                });
            } elseif (DB::getSchemaBuilder()->hasTable('model_has_permissions')) {
                $query->whereIn('id', function ($sub) use ($permission, $userModel) {
                    $sub->select('model_id')
                        ->from('model_has_permissions')
                        ->where('model_type', $userModel)
                        ->where('permission_id', function ($p) use ($permission) {
                            $p->select('id')->from('permissions')->where('name', $permission)->limit(1);
                        });
                });
            }
        }

        return $query->pluck('id')->map(fn($v) => (int)$v)->all();
    }
}
