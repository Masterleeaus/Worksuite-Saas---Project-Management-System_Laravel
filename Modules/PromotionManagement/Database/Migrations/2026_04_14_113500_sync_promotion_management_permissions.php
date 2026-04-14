<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    private array $permissions = [
        'discount_view'                  => 'View Discounts',
        'discount_add'                   => 'Create Discounts',
        'discount_update'                => 'Edit Discounts',
        'discount_delete'                => 'Delete Discounts',
        'discount_manage_status'         => 'Manage Discount Status',
        'discount_export'                => 'Export Discounts',
        'coupon_view'                    => 'View Coupons',
        'coupon_add'                     => 'Create Coupons',
        'coupon_update'                  => 'Edit Coupons',
        'coupon_delete'                  => 'Delete Coupons',
        'coupon_manage_status'           => 'Manage Coupon Status',
        'coupon_export'                  => 'Export Coupons',
        'campaign_view'                  => 'View Campaigns',
        'campaign_add'                   => 'Create Campaigns',
        'campaign_update'                => 'Edit Campaigns',
        'campaign_delete'                => 'Delete Campaigns',
        'campaign_manage_status'         => 'Manage Campaign Status',
        'campaign_export'                => 'Export Campaigns',
        'banner_view'                    => 'View Banners',
        'banner_add'                     => 'Create Banners',
        'banner_update'                  => 'Edit Banners',
        'banner_delete'                  => 'Delete Banners',
        'banner_manage_status'           => 'Manage Banner Status',
        'banner_export'                  => 'Export Banners',
        'push_notification_view'         => 'View Push Notifications',
        'push_notification_add'          => 'Create Push Notifications',
        'push_notification_update'       => 'Edit Push Notifications',
        'push_notification_delete'       => 'Delete Push Notifications',
        'push_notification_manage_status'=> 'Manage Push Notification Status',
        'push_notification_export'       => 'Export Push Notifications',
        'advertisement_view'             => 'View Advertisements',
        'advertisement_add'              => 'Create Advertisements',
        'advertisement_update'           => 'Edit Advertisements',
        'advertisement_delete'           => 'Delete Advertisements',
        'advertisement_manage_status'    => 'Manage Advertisement Status',
        'advertisement_export'           => 'Export Advertisements',
    ];

    private array $legacyPermissionNames = [
        'view_promotions',
        'create_promotions',
        'edit_promotions',
        'delete_promotions',
        'manage_promotion_status',
        'export_promotions',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        $cols = Schema::getColumnListing('permissions');
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

        if ($moduleId && in_array('module_id', $cols, true)) {
            DB::table('permissions')
                ->where('module_id', $moduleId)
                ->whereIn('name', $this->legacyPermissionNames)
                ->delete();
        }

        $this->grantToAdminRole();
    }

    public function down(): void
    {
        if (!Schema::hasTable('permissions')) {
            return;
        }

        DB::table('permissions')->whereIn('name', array_keys($this->permissions))->delete();
    }

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

        return DB::table('modules')->insertGetId([
            'module_name'   => 'PromotionManagement',
            'description'   => 'Promotion & Coupon Management',
            'is_superadmin' => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
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

        $prCols = Schema::getColumnListing('permission_role');
        $hasPermissionTypeId = in_array('permission_type_id', $prCols, true);

        foreach ($permissionIds as $permissionId) {
            $exists = DB::table('permission_role')
                ->where('permission_id', $permissionId)
                ->where('role_id', $adminRole->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $row = [
                'permission_id' => $permissionId,
                'role_id'       => $adminRole->id,
            ];

            if ($hasPermissionTypeId) {
                $row['permission_type_id'] = 4;
            }

            DB::table('permission_role')->insert($row);
        }
    }
};
