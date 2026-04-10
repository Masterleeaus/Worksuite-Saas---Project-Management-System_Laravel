<?php

namespace Modules\QualityControl\Policies;

use App\Models\User;
use Modules\QualityControl\Support\InspectionPermissions;

class InspectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(InspectionPermissions::VIEW)
            || $user->hasPermissionTo(InspectionPermissions::LEGACY_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(InspectionPermissions::CREATE)
            || $user->hasPermissionTo(InspectionPermissions::LEGACY_CREATE);
    }
}
