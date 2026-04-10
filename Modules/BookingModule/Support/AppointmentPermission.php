<?php

namespace Modules\BookingModule\Support;

use Illuminate\Contracts\Auth\Authenticatable;

class AppointmentPermission
{
    public static function check($user, string $permission): bool
    {
        if (! $user instanceof Authenticatable) {
            return false;
        }

        $candidates = array_values(array_unique(array_filter([
            $permission,
            str_replace('.', ' ', $permission),
            str_replace('_', ' ', $permission),
            str_replace('-', ' ', $permission),
            str_replace(['.', '-'], '_', $permission),
            str_replace(' ', '_', $permission),
        ])));

        foreach ($candidates as $candidate) {
            if (self::viaUserMethods($user, $candidate) || self::viaRoleMethods($user, $candidate)) {
                return true;
            }
        }

        return false;
    }

    protected static function viaUserMethods($user, string $permission): bool
    {
        $methods = [
            'permission',
            'hasPermission',
            'hasPermissionTo',
            'can',
            'isAbleTo',
            'ability',
        ];

        foreach ($methods as $method) {
            if (! method_exists($user, $method)) {
                continue;
            }

            try {
                $result = $method === 'ability'
                    ? $user->{$method}([], [$permission])
                    : $user->{$method}($permission);

                if ($result) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Ignore incompatible permission APIs and continue trying.
            }
        }

        return false;
    }

    protected static function viaRoleMethods($user, string $permission): bool
    {
        foreach (['role', 'roles'] as $relation) {
            if (! method_exists($user, $relation)) {
                continue;
            }

            try {
                $roles = $user->{$relation};
                if ($roles instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $roles = $roles->getResults();
                }
                $roles = $roles ? collect($roles) : collect();

                foreach ($roles as $role) {
                    foreach (['hasPermission', 'hasPermissionTo', 'can'] as $method) {
                        if (method_exists($role, $method) && $role->{$method}($permission)) {
                            return true;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Ignore role-relation incompatibilities.
            }
        }

        return false;
    }
}
