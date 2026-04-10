<?php

namespace Modules\BookingModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('permissions')) {
            return;
        }

        $permissions = [
            'view_booking',
            'add_booking',
            'edit_booking',
            'delete_booking',
            'view_booking_pages',
            'manage_booking_pages',
            'view_booking_page_requests',
            'manage_dispatch_board',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission],
                [
                    'guard_name' => 'web',
                    'display_name' => ucwords(str_replace('_', ' ', $permission)),
                    'module' => 'BookingModule',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
