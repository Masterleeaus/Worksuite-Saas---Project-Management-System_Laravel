<?php

namespace Modules\Blogs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BlogsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('modules') || !Schema::hasTable('permissions')) {
            return;
        }

        $module = DB::table('modules')->where('module_name', 'blogs')->first();

        if (!$module) {
            return;
        }

        $permissions = [
            'view_blogs' => 'View Blogs',
            'create_blogs' => 'Create Blogs',
            'edit_blogs' => 'Edit Blogs',
            'delete_blogs' => 'Delete Blogs',
            'manage_blogs' => 'Manage Blogs',
            'publish_blogs' => 'Publish Blogs',
            'export_blogs' => 'Export Blogs',
        ];

        foreach ($permissions as $name => $displayName) {
            $permission = DB::table('permissions')->where('name', $name)->first();
            if ($permission) {
                DB::table('permissions')->where('id', $permission->id)->update([
                    'display_name' => $displayName,
                    'module_id' => $module->id,
                    'allowed_permissions' => '{"all":4, "none":5}',
                    'is_custom' => 1,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('permissions')->insert([
                    'name' => $name,
                    'display_name' => $displayName,
                    'module_id' => $module->id,
                    'allowed_permissions' => '{"all":4, "none":5}',
                    'is_custom' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
