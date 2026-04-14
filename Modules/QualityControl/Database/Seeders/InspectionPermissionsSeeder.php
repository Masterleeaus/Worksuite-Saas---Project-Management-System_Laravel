<?php

namespace Modules\QualityControl\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectionPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_quality_control', 'display_name' => 'View Quality Control'],
            ['name' => 'add_quality_control', 'display_name' => 'Add Quality Control'],
            ['name' => 'edit_quality_control', 'display_name' => 'Edit Quality Control'],
            ['name' => 'delete_quality_control', 'display_name' => 'Delete Quality Control'],
            ['name' => 'inspection.view', 'display_name' => 'View Quality Control (Legacy)'],
            ['name' => 'inspection.create', 'display_name' => 'Create Quality Control (Legacy)'],
            ['name' => 'inspection.update', 'display_name' => 'Update Quality Control (Legacy)'],
            ['name' => 'inspection.delete', 'display_name' => 'Delete Quality Control (Legacy)'],
        ];

        try {
            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $permission['name']],
                    $permission
                );
            }
        } catch (\Throwable $e) {
            // Safe fallback for hosts that seed permissions elsewhere.
        }
    }
}
