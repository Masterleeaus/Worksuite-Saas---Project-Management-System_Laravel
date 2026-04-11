<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Inserts PromotionManagement permissions and grants them to the admin role.
 *
 * Uses updateOrInsert so that re-running is idempotent.
 * Uses the core `permissions` table directly to avoid Entrust dependency issues
 * during the migration phase.
 */
return new class extends Migration {

    private array $permissions = [
        'view_promotions'          => 'View Promotions',
        'create_promotions'        => 'Create Promotions',
        'edit_promotions'          => 'Edit Promotions',
        'delete_promotions'        => 'Delete Promotions',
        'manage_promotion_status'  => 'Manage Promotion Status',
        'export_promotions'        => 'Export Promotions',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $cols     = Schema::getColumnListing('permissions');
        $moduleId = $this->resolveModuleId();

        foreach ($this->permissions as $name => $displayName) {
            $data = [
                'display_name' => $displayName,
                'updated_at'   => now(),
            ];

            if (in_array('allowed_permissions', $cols, true)) {
                $data['allowed_permissions'] = \App\Models\Permission::ALL_NONE;
            }
            if ($moduleId && in_array('module_id', $cols, true)) {
                $data['module_id'] = $moduleId;
            }

            DB::table('permissions')->updateOrInsert(
                ['name' => $name],
                array_merge($data, ['created_at' => now()])
            );
        }

        // Grant all permissions to admin role
        $this->grantToAdminRole();
    }

    public function down(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        DB::table('permissions')
            ->whereIn('name', array_keys($this->permissions))
            ->delete();
    }

    // -------------------------------------------------------------------------

    private function resolveModuleId(): ?int
    {
        if (!Schema::hasTable('modules')) {
            return null;
        }

        $cols = Schema::getColumnListing('modules');
        if (!in_array('module_name', $cols, true)) {
            return null;
        }

        $row = DB::table('modules')->where('module_name', 'PromotionManagement')->first();

        if ($row) {
            return (int) $row->id;
        }

        // Create module row if it does not exist
        return DB::table('modules')->insertGetId([
            'module_name'    => 'PromotionManagement',
            'description'    => 'Promotion & Coupon Management',
            'is_superadmin'  => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    private function grantToAdminRole(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('permission_role')) {
            return;
        }

        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        if (!$adminRole) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('name', array_keys($this->permissions))
            ->pluck('id');

        $prCols        = Schema::getColumnListing('permission_role');
        $hasPermission = in_array('permission_id', $prCols, true);
        $hasRole       = in_array('role_id', $prCols, true);
        $hasValue      = in_array('permission_type_id', $prCols, true);

        foreach ($permissionIds as $permId) {
            $exists = DB::table('permission_role')
                ->where('permission_id', $permId)
                ->where('role_id', $adminRole->id)
                ->exists();

            if (!$exists) {
                $row = [
                    'permission_id' => $permId,
                    'role_id'       => $adminRole->id,
                ];
                if ($hasValue) {
                    $row['permission_type_id'] = 4; // 'all'
                }
                DB::table('permission_role')->insert($row);
            }
        }
    }
};
