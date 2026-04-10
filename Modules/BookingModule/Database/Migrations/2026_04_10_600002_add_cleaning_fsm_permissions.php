<?php

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Modules\BookingModule\Entities\BookingModuleSetting;

/**
 * Add FSM / cleaning-specific permissions for the BookingModule.
 *
 * These permissions cover the cleaning-business workflow introduced in the
 * CleaningBooking FSM upgrade:
 *   - view_bookings  / create_bookings  — basic access
 *   - assign_cleaners                   — dispatch board: assign a cleaner to a job
 *   - complete_booking                  — advance status to 'completed'
 *   - cancel_booking                    — cancel any booking
 */
return new class extends Migration
{
    private const FSM_PERMISSIONS = [
        'view_bookings',
        'create_bookings',
        'assign_cleaners',
        'complete_booking',
        'cancel_booking',
    ];

    public function up(): void
    {
        $module = Module::firstOrCreate(['module_name' => BookingModuleSetting::MODULE_NAME]);

        $permissionsToCreate = [
            ['name' => 'view_bookings',   'display_name' => 'View Bookings (FSM)',      'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'create_bookings', 'display_name' => 'Create Bookings (FSM)',    'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'assign_cleaners', 'display_name' => 'Assign Cleaners',          'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'complete_booking','display_name' => 'Complete / Update Booking','module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
            ['name' => 'cancel_booking',  'display_name' => 'Cancel Booking',           'module_id' => $module->id, 'allowed_permissions' => Permission::ALL_NONE],
        ];

        foreach ($permissionsToCreate as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', self::FSM_PERMISSIONS)->delete();
    }
};
