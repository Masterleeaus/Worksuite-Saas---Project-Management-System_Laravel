<?php

namespace App\Traits;

use App\Models\Acl\AdminUserRole;
use App\Models\Acl\RolePrivilege;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

trait HasPermissionsTrait
{
    public function getModulesPermissions()
    {
        $return = false;
        $currentUri = Route::getFacadeRoot()->current()->uri();
        $adminUserId = Auth::guard('admin')->user()->id;
        $resultAdminRoles = AdminUserRole::where('admin_ID', $adminUserId)->get();
        if ($resultAdminRoles) {
            foreach ($resultAdminRoles as $rowAdminRole) {
                $result = RolePrivilege::hasPermission($rowAdminRole->role_ID, $currentUri);
                if ($result) {
                    $return = $result;
                }
            }
        }
        return $return;
    }

    public static function getModulesPermissionsBySlug($slug)
    {
        $return = false;
        $adminUserId = Auth::guard('admin')->user()->id;
        $resultAdminRoles = AdminUserRole::where('admin_ID', $adminUserId)->get();

        if ($resultAdminRoles) {
            foreach ($resultAdminRoles as $rowAdminRole) {
                $result = RolePrivilege::hasPermission($rowAdminRole->role_ID, $slug);
                if ($result) {
                    $return = $result;
                }
            }
        }
        return $return;
    }

    public function getPermittedMenu()
    {
        $adminUserId = $this->id; // Using $this because trait is used in Admin model
        $roleIds = AdminUserRole::where('admin_ID', $adminUserId)->pluck('role_ID');

        $permittedModuleIds = RolePrivilege::whereIn('role_ID', $roleIds)->pluck('module_ID')->unique();

        return \App\Models\Acl\ModuleCategory::where('active_status', 1)
            ->with(['modules' => function($query) use ($permittedModuleIds) {
                $query->whereIn('ID', $permittedModuleIds)
                      ->where('show_in_menu', 1)
                      ->where('active_status', 1)
                      ->orderBy('display_order', 'ASC');
            }])
            ->whereHas('modules', function($query) use ($permittedModuleIds) {
                $query->whereIn('ID', $permittedModuleIds)
                      ->where('show_in_menu', 1)
                      ->where('active_status', 1);
            })
            ->orderBy('display_order', 'ASC')
            ->get();
    }
}
