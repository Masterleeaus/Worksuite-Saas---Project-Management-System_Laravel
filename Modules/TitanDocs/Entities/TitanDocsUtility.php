<?php

namespace Modules\TitanDocs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\WorkSpace;
use Rawilk\Settings\Support\Context;




class TitanDocsUtility extends Model
{
    use HasFactory;

    
    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $staff_permission = [
            'ai document manage',
            'ai document generate',
            'ai document generate PDF',
            'ai document generate Word',
            'ai document generate TXT',
            'ai document copy',
            'ai document save',
            'ai document create',
            'ai document view',
            'document history manage',
            'document history edit',
            'sidebar ai manage',
        ];

        if ($role_id == Null) {

            // staff
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if(!$role->hasPermission($permission_v))
                    {
                        $role->givePermission($permission);
                    }
                    
                }
            }
        } else {
            if ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if(!$roles_v->hasPermission($permission_v))
                    {
                        $roles_v->givePermission($permission);
                    }
                }
            }
        }
    }
    
}
