<?php

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Modules\BookingModule\Entities\BookingModuleSetting;

return new class extends Migration
{
    public function up(): void
    {
        $module = Module::firstOrCreate(['module_name' => BookingModuleSetting::MODULE_NAME]);

        foreach ([
            ['name' => 'view_booking_pages', 'display_name' => 'View Booking Pages'],
            ['name' => 'manage_booking_pages', 'display_name' => 'Manage Booking Pages'],
        ] as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'module_id' => $module->id,
                'allowed_permissions' => Permission::ALL_NONE,
            ]);
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', ['view_booking_pages', 'manage_booking_pages'])->delete();
    }
};
