<?php

namespace App\Services\Admin\Acl\Services;

use App\Models\Acl\Role;

class RoleService
{
    public function getAllRoles()
    {
        return Role::orderBy('display_order', 'asc')->get();
    }

    public function createRole(array $data)
    {
        $role = Role::create($data);
        if (isset($data['module_ids'])) {
            $this->syncPermissions($role, $data['module_ids']);
        }
        return $role;
    }

    public function updateRole(Role $role, array $data)
    {
        $role->update($data);
        if (isset($data['module_ids'])) {
            $this->syncPermissions($role, $data['module_ids']);
        }
        return $role;
    }

    public function syncPermissions(Role $role, array $moduleIds)
    {
        $role->modules()->sync($moduleIds);
    }

    public function deleteRole(Role $role)
    {
        $role->modules()->detach();
        return $role->delete();
    }
}
