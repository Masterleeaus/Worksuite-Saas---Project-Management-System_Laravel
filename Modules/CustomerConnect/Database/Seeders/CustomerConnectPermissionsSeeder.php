<?php

namespace Modules\CustomerConnect\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerConnectPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create module row if modules table exists (WorkSuite core)
        $moduleId = null;
        if (Schema::hasTable('modules') && Schema::hasColumn('modules', 'module_name')) {
            $row = DB::table('modules')->where('module_name', 'CustomerConnect')->first();
            if (!$row) {
                $moduleId = DB::table('modules')->insertGetId([
                    'module_name' => 'CustomerConnect',
                    'description' => 'Customer Connect (multi-channel campaigns)',
                    'is_superadmin' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $moduleId = $row->id;
            }
        }

                $permissions = [
            ['key' => 'customerconnect.view', 'name' => 'View Customer Connect', 'desc' => 'View module pages'],
            ['key' => 'customerconnect.manage', 'name' => 'Manage Customer Connect', 'desc' => 'Create/edit campaigns, settings'],
            ['key' => 'customerconnect.campaigns', 'name' => 'Manage Campaigns', 'desc' => 'Create and edit campaigns'],
            ['key' => 'customerconnect.audiences', 'name' => 'Manage Audiences', 'desc' => 'Create and edit audiences'],
            ['key' => 'customerconnect.runs', 'name' => 'View Runs', 'desc' => 'View and dispatch runs'],
            ['key' => 'customerconnect.inbox.view', 'name' => 'View Inbox', 'desc' => 'View CustomerConnect inbox and threads'],
            ['key' => 'customerconnect.inbox.reply', 'name' => 'Reply in Inbox', 'desc' => 'Send outbound messages from inbox'],
            ['key' => 'customerconnect.inbox.assign', 'name' => 'Assign Threads', 'desc' => 'Assign and close threads'],
            ['key' => 'customerconnect.deliveries', 'name' => 'View Deliveries', 'desc' => 'View delivery status'],
            ['key' => 'customerconnect.recipes', 'name' => 'Use Recipes', 'desc' => 'Install recipe campaigns'],
            ['key' => 'customerconnect.exports', 'name' => 'Export', 'desc' => 'Export campaign data'],
            ['key' => 'customerconnect.settings', 'name' => 'Settings', 'desc' => 'Manage suppression/unsubscribes'],
        ];

        // WorkSuite newer schema: permissions table with module_id
        if (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'module_id')) {
            foreach ($permissions as $p) {
                $exists = DB::table('permissions')
                    ->where('name', $p['key'])
                    ->when($moduleId, fn($q) => $q->where('module_id', $moduleId))
                    ->exists();

                if (!$exists) {
                    DB::table('permissions')->insert([
                        'name' => $p['key'],
                        'display_name' => $p['label'],
                        'description' => null,
                        'module_id' => $moduleId ?? 1,
                        'is_custom' => 1,
                        'allowed_permissions' => json_encode(['all' => 4, 'added' => 1, 'none' => 5]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Attach to company role if pivot exists
            if (Schema::hasTable('roles') && Schema::hasTable('permission_role')) {
                $companyRole = DB::table('roles')->where('name', 'company')->first();
                if ($companyRole) {
                    foreach ($permissions as $p) {
                        $perm = DB::table('permissions')
                            ->where('name', $p['key'])
                            ->when($moduleId, fn($q) => $q->where('module_id', $moduleId))
                            ->first();

                        if ($perm) {
                            $pivotExists = DB::table('permission_role')
                                ->where('permission_id', $perm->id)
                                ->where('role_id', $companyRole->id)
                                ->exists();
                            if (!$pivotExists) {
                                $data = [
                                    'permission_id' => $perm->id,
                                    'role_id' => $companyRole->id,
                                ];
                                if (Schema::hasColumn('permission_role', 'permission_type_id')) {
                                    $data['permission_type_id'] = 4; // "all" in WorkSuite defaults
                                }
                                DB::table('permission_role')->insert($data);
                            }
                        }
                    }
                }
            }

            return;
        }

        // Legacy schema fallback: some builds store module as string + guard_name
        if (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'module')) {
            foreach ($permissions as $p) {
                $exists = DB::table('permissions')->where('name', $p['key'])->where('module', 'CustomerConnect')->exists();
                if (!$exists) {
                    DB::table('permissions')->insert([
                        'name' => $p['key'],
                        'guard_name' => Schema::hasColumn('permissions','guard_name') ? 'web' : null,
                        'module' => 'CustomerConnect',
                        'created_by' => Schema::hasColumn('permissions','created_by') ? 0 : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}