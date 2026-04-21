<?php

use App\Models\Company;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Scopes\CompanyScope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Harden BookingModule registration.
 *
 * Ensures:
 * - The module entry exists in the `modules` table (appears in Packages / module management UI)
 * - ModuleSetting rows exist for every existing company so the module can be enabled per-company
 * - All appointment permissions referenced by menus and policies are seeded
 *
 * Safe to run on fresh installs and on existing databases (all operations are idempotent).
 */
return new class extends Migration
{
    private const MODULE_NAME = 'bookingmodule';

    /**
     * Full permission list for BookingModule (covers classic booking + appointment/scheduling flows).
     */
    private const PERMISSIONS = [
        // Classic booking permissions
        ['name' => 'add_booking',                'display_name' => 'Add Booking'],
        ['name' => 'view_booking',               'display_name' => 'View Booking'],
        ['name' => 'edit_booking',               'display_name' => 'Edit Booking'],
        ['name' => 'delete_booking',             'display_name' => 'Delete Booking'],
        ['name' => 'view_booking_pages',         'display_name' => 'View Booking Pages'],
        ['name' => 'manage_booking_pages',       'display_name' => 'Manage Booking Pages'],
        ['name' => 'view_booking_page_requests', 'display_name' => 'View Booking Page Requests'],
        ['name' => 'manage_dispatch_board',      'display_name' => 'Manage Dispatch Board'],
        // FSM / cleaning permissions
        ['name' => 'view_bookings',              'display_name' => 'View Bookings (FSM)'],
        ['name' => 'create_bookings',            'display_name' => 'Create Bookings (FSM)'],
        ['name' => 'assign_cleaners',            'display_name' => 'Assign Cleaners'],
        ['name' => 'complete_booking',           'display_name' => 'Complete / Update Booking'],
        ['name' => 'cancel_booking',             'display_name' => 'Cancel Booking'],
    ];

    public function up(): void
    {
        // 1. Ensure module entry exists (needed for permission management UI)
        if (Schema::hasTable('modules')) {
            $module = Module::withoutGlobalScopes()->firstOrCreate(['module_name' => self::MODULE_NAME]);

            // 2. Seed permissions
            if (Schema::hasTable('permissions')) {
                foreach (self::PERMISSIONS as $permData) {
                    \App\Models\Permission::firstOrCreate(
                        ['name' => $permData['name']],
                        [
                            'display_name'        => $permData['display_name'],
                            'module_id'           => $module->id,
                            'allowed_permissions' => \App\Models\Permission::ALL_NONE,
                        ]
                    );
                }
            }
        }

        // 3. Ensure ModuleSetting rows exist for all existing companies
        if (Schema::hasTable('module_settings') && Schema::hasTable('companies')) {
            $roles = ['admin', 'employee'];

            Company::chunk(100, function ($companies) use ($roles) {
                foreach ($companies as $company) {
                    foreach ($roles as $role) {
                        $exists = ModuleSetting::withoutGlobalScope(CompanyScope::class)
                            ->where('module_name', self::MODULE_NAME)
                            ->where('type', $role)
                            ->where('company_id', $company->id)
                            ->exists();

                        if (!$exists) {
                            ModuleSetting::create([
                                'module_name' => self::MODULE_NAME,
                                'type'        => $role,
                                'company_id'  => $company->id,
                                'status'      => 'active',
                                'is_allowed'  => 1,
                            ]);
                        }
                    }
                }
            });
        }
    }

    public function down(): void
    {
        // Remove module_settings rows added by this migration
        if (Schema::hasTable('module_settings')) {
            ModuleSetting::withoutGlobalScope(CompanyScope::class)
                ->where('module_name', self::MODULE_NAME)
                ->delete();
        }
    }
};
