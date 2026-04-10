<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acl\Role;
use App\Models\Acl\ModuleCategory;
use App\Models\Acl\Module;
use App\Models\Acl\RolePrivilege;

class PlatformModulesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get Super Admin Role
        $role = Role::where('role_name', 'Super Admin')->first() ?? Role::create(['role_name' => 'Super Admin', 'active_status' => 1, 'display_order' => 1]);

        // 2. Create "Platform Management" Category
        $category = ModuleCategory::updateOrCreate(
            ['name' => 'Platform'],
            ['active_status' => 1, 'display_order' => 2]
        );

        // 3. Define New Modules
        $modules = [
            // Tenants
            [
                'module_name' => 'Businesses',
                'slug' => 'tenants',
                'route' => 'admin/tenants',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 1
            ],
            [
                'module_name' => 'View Business',
                'slug' => 'tenants/view',
                'route' => 'admin/tenants/view/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 2
            ],
            [
                'module_name' => 'Edit Business',
                'slug' => 'tenants/edit',
                'route' => 'admin/tenants/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 3
            ],
            [
                'module_name' => 'Delete Business',
                'slug' => 'tenants/delete',
                'route' => 'admin/tenants/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 4
            ],
             [
                'module_name' => 'Impersonate Business',
                'slug' => 'tenants/impersonate',
                'route' => 'admin/tenants/impersonate/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 5
            ],

            // Plans
            [
                'module_name' => 'Plans',
                'slug' => 'plans',
                'route' => 'admin/plans',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 6
            ],
            [
                'module_name' => 'Add Plan',
                'slug' => 'plans/add',
                'route' => 'admin/plans/add',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 7
            ],
            [
                'module_name' => 'Edit Plan',
                'slug' => 'plans/edit',
                'route' => 'admin/plans/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 8
            ],
            [
                'module_name' => 'Delete Plan',
                'slug' => 'plans/delete',
                'route' => 'admin/plans/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 9
            ],

            // Coupons
            [
                'module_name' => 'Coupons',
                'slug' => 'coupons',
                'route' => 'admin/coupons',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 10
            ],
            [
                'module_name' => 'Add Coupon',
                'slug' => 'coupons/add',
                'route' => 'admin/coupons/add',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 11
            ],
            [
                'module_name' => 'Edit Coupon',
                'slug' => 'coupons/edit',
                'route' => 'admin/coupons/edit/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 12
            ],
            [
                'module_name' => 'Delete Coupon',
                'slug' => 'coupons/delete',
                'route' => 'admin/coupons/delete/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 13
            ],

            // Settings
            [
                'module_name' => 'Settings',
                'slug' => 'settings',
                'route' => 'admin/settings',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 14
            ],
            
            // Audit Logs
            [
                'module_name' => 'Audit Logs',
                'slug' => 'audit-logs',
                'route' => 'admin/audit-logs',
                'category_id' => $category->ID,
                'show_in_menu' => 1,
                'active_status' => 1,
                'display_order' => 15
            ],
            [
                'module_name' => 'View Audit Log',
                'slug' => 'audit-logs/view',
                'route' => 'admin/audit-logs/view/{id}',
                'category_id' => $category->ID,
                'show_in_menu' => 0,
                'active_status' => 1,
                'display_order' => 16
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::updateOrCreate(
                ['slug' => $moduleData['slug']],
                $moduleData
            );

            // 4. Assign to Super Admin
            RolePrivilege::updateOrCreate(
                ['role_ID' => $role->ID, 'module_ID' => $module->ID]
            );
        }
    }
}
