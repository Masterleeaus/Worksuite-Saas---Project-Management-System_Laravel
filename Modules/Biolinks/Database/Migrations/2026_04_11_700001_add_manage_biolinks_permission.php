<?php

use App\Models\Company;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Add manage_biolinks permission and enable biolinks module for all existing companies.
 */
return new class extends Migration
{
    public function up(): void
    {
        $moduleName = 'biolinks';

        $module = Module::firstOrCreate(['module_name' => $moduleName]);

        // Add manage_biolinks permission (other permissions already seeded by existing migration)
        $permission = Permission::firstOrCreate(
            ['name' => 'manage_biolinks', 'module_id' => $module->id],
            [
                'display_name'         => 'Manage Biolinks',
                'is_custom'            => 1,
                'allowed_permissions'  => Permission::ALL_NONE,
            ]
        );

        foreach (Company::all() as $company) {
            // Assign permission to the company admin role
            $adminRole = Role::where('name', 'admin')->where('company_id', $company->id)->first();
            if ($adminRole) {
                $pr = PermissionRole::firstOrNew([
                    'permission_id' => $permission->id,
                    'role_id'       => $adminRole->id,
                ]);
                $pr->permission_type_id = 4; // All
                $pr->save();
            }

            // Enable the module in module_settings for each company
            if (Schema::hasTable('module_settings')) {
                $cols   = Schema::getColumnListing('module_settings');
                $exists = DB::table('module_settings')
                    ->where('company_id', $company->id)
                    ->where('module_name', $moduleName)
                    ->exists();

                if (!$exists) {
                    $row = ['module_name' => $moduleName, 'company_id' => $company->id];

                    if (in_array('status', $cols, true)) {
                        $row['status'] = 'active';
                    } elseif (in_array('is_active', $cols, true)) {
                        $row['is_active'] = 1;
                    }

                    if (in_array('user_id', $cols, true)) {
                        $row['user_id'] = null;
                    }

                    DB::table('module_settings')->insert($row);
                } else {
                    $update = [];
                    if (in_array('status', $cols, true)) {
                        $update['status'] = 'active';
                    } elseif (in_array('is_active', $cols, true)) {
                        $update['is_active'] = 1;
                    }
                    if (!empty($update)) {
                        DB::table('module_settings')
                            ->where('company_id', $company->id)
                            ->where('module_name', $moduleName)
                            ->update($update);
                    }
                }
            }
        }

        // Grant permission to all admin users directly
        if (method_exists(User::class, 'allAdmins')) {
            $adminUsers = User::allAdmins();
        } else {
            $adminUsers = User::where('role_id', 1)->get();
        }

        foreach ($adminUsers as $adminUser) {
            $up = UserPermission::firstOrNew([
                'user_id'       => $adminUser->id,
                'permission_id' => $permission->id,
            ]);
            $up->permission_type_id = 4; // All
            $up->save();
        }
    }

    public function down(): void
    {
        // No-op
    }
};
