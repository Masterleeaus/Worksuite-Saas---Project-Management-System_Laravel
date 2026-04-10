<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Role
        $role = \App\Models\Acl\Role::updateOrCreate(
            ['role_name' => 'Super Admin'],
            ['active_status' => 1, 'display_order' => 1]
        );

        // 2. Create Module Category
        $category = \App\Models\Acl\ModuleCategory::updateOrCreate(
            ['name' => 'Access Control'],
            ['active_status' => 1, 'display_order' => 1]
        );

        // 3. Create Modules
        $modules = [
            [
                'module_name' => 'Admin Users',
                'slug' => 'acl/users',
                'route' => 'admin/acl/users',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 1
            ],
            [
                'module_name' => 'Roles',
                'slug' => 'acl/roles',
                'route' => 'admin/acl/roles',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 2
            ],
            [
                'module_name' => 'Modules',
                'slug' => 'acl/modules',
                'route' => 'admin/acl/modules',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 3
            ],
            [
                'module_name' => 'Module Categories',
                'slug' => 'acl/categories',
                'route' => 'admin/acl/categories',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 4
            ],
            [
                'module_name' => 'Add User',
                'slug' => 'acl/users/add',
                'route' => 'admin/acl/users/add',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 5
            ],
            [
                'module_name' => 'Edit User',
                'slug' => 'acl/users/edit',
                'route' => 'admin/acl/users/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 6
            ],
            [
                'module_name' => 'Delete User',
                'slug' => 'acl/users/delete',
                'route' => 'admin/acl/users/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 7
            ],
            [
                'module_name' => 'Add Role',
                'slug' => 'acl/roles/add',
                'route' => 'admin/acl/roles/add',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 8
            ],
            [
                'module_name' => 'Edit Role',
                'slug' => 'acl/roles/edit',
                'route' => 'admin/acl/roles/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 9
            ],
            [
                'module_name' => 'Delete Role',
                'slug' => 'acl/roles/delete',
                'route' => 'admin/acl/roles/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 10
            ],
            [
                'module_name' => 'Add Module',
                'slug' => 'acl/modules/add',
                'route' => 'admin/acl/modules/add',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 11
            ],
            [
                'module_name' => 'Edit Module',
                'slug' => 'acl/modules/edit',
                'route' => 'admin/acl/modules/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 12
            ],
            [
                'module_name' => 'Delete Module',
                'slug' => 'acl/modules/delete',
                'route' => 'admin/acl/modules/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 13
            ],
            [
                'module_name' => 'Add Category',
                'slug' => 'acl/categories/add',
                'route' => 'admin/acl/categories/add',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 14
            ],
            [
                'module_name' => 'Edit Category',
                'slug' => 'acl/categories/edit',
                'route' => 'admin/acl/categories/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 15
            ],
            [
                'module_name' => 'Delete Category',
                'slug' => 'acl/categories/delete',
                'route' => 'admin/acl/categories/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 16
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = \App\Models\Acl\Module::updateOrCreate(
                ['slug' => $moduleData['slug']],
                $moduleData
            );

            // 4. Assign Privilege to Role
            \App\Models\Acl\RolePrivilege::updateOrCreate(
                ['role_ID' => $role->ID, 'module_ID' => $module->ID]
            );
        }

        // 5. Assign Role to ALL existing Admins for testing/visibility
        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
            \App\Models\Acl\AdminUserRole::updateOrCreate(
                ['admin_ID' => $admin->id, 'role_ID' => $role->ID]
            );
        }
    }
}
