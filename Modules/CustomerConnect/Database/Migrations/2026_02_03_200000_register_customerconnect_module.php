<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Ensure module appears in Super Admin -> Packages (Plans)
        $module = Module::firstOrCreate(
            ['module_name' => 'customerconnect'],
            ['is_superadmin' => 0]
        );

        // 2) Minimum permission: view_customerconnect
        if (Schema::hasTable('permissions')) {
            $cols = Schema::getColumnListing('permissions');
            $row = [];

            if (in_array('name', $cols, true)) {
                $row['name'] = 'view_customerconnect';
            }
            if (in_array('display_name', $cols, true)) {
                $row['display_name'] = 'View Titan Connect';
            }
            if (in_array('guard_name', $cols, true)) {
                $row['guard_name'] = 'web';
            }
            if (in_array('module_id', $cols, true)) {
                $row['module_id'] = $module->id;
            }
            if (in_array('created_at', $cols, true)) {
                $row['created_at'] = now();
            }
            if (in_array('updated_at', $cols, true)) {
                $row['updated_at'] = now();
            }

            // Only insert if we have at least the name column.
            if (!empty($row) && array_key_exists('name', $row)) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => 'view_customerconnect'],
                    $row
                );
            }
        }
    }

    public function down(): void
    {
        // Avoid destructive downs in Worksuite modules (packages may reference this).
    }
};