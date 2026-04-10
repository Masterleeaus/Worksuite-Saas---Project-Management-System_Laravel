<?php

use App\Models\Company;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Modules\BookingModule\Entities\BookingModuleSetting;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure module exists in `modules` table so it appears in Packages UI
        $module = Module::firstOrCreate(['module_name' => BookingModuleSetting::MODULE_NAME]);

        // Minimal permission set (follows Worksuite module convention)
        $permissions = [
            ['name' => 'add_booking',    'display_name' => 'Add Booking',    'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'view_booking',   'display_name' => 'View Booking',   'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'edit_booking',   'display_name' => 'Edit Booking',   'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'delete_booking', 'display_name' => 'Delete Booking', 'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // Ensure module setting entries exist for every company (needed for per-company enablement)
        foreach (Company::all() as $company) {
            BookingModuleSetting::addModuleSetting($company);
        }
    }

    public function down(): void
    {
        // Remove permissions added by this migration
        $permissionNames = ['add_booking', 'view_booking', 'edit_booking', 'delete_booking'];
        Permission::whereIn('name', $permissionNames)->delete();

        // Remove module entry
        Module::where('module_name', BookingModuleSetting::MODULE_NAME)->delete();

        // Remove module_settings rows for this module
        \App\Models\ModuleSetting::where('module_name', BookingModuleSetting::MODULE_NAME)->delete();
    }
};
