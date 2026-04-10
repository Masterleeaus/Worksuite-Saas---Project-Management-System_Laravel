<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Register the CustomerConnect module in the Super Admin modules list and
 * subscription_package_features so it can be toggled per tenant / plan.
 *
 * Also seeds the portal-specific permissions:
 *   access_customer_portal, rebook_online, view_own_invoices, manage_own_properties
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Modules table (Super Admin module list) ────────────────────────
        if (Schema::hasTable('modules')) {
            $exists = DB::table('modules')->where('module_name', 'customerconnect')->exists();
            if (!$exists) {
                DB::table('modules')->insert([
                    'module_name' => 'customerconnect',
                    'description' => 'Customer self-service portal — rebooking, invoices, payments and property management',
                    'is_superadmin' => 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            } else {
                DB::table('modules')->where('module_name', 'customerconnect')->update([
                    'description' => 'Customer self-service portal — rebooking, invoices, payments and property management',
                    'updated_at'  => now(),
                ]);
            }
        }

        // ── 2. subscription_package_features ─────────────────────────────────
        if (!Schema::hasTable('subscription_package_features') || !Schema::hasTable('subscription_packages')) {
            // Tables may not exist yet (dev / fresh install) — skip silently.
            return;
        }

        $cols = Schema::getColumnListing('subscription_package_features');
        $hasFeature = in_array('feature', $cols, true);
        $hasUuid    = in_array('id', $cols, true);
        $hasPkgId   = in_array('subscription_package_id', $cols, true);

        if (!$hasFeature || !$hasPkgId) {
            return;
        }

        $packages = DB::table('subscription_packages')->get();

        foreach ($packages as $package) {
            $existing = DB::table('subscription_package_features')
                ->where('subscription_package_id', $package->id)
                ->where('feature', 'customer_connect')
                ->exists();

            if (!$existing) {
                $row = [
                    'feature'                 => 'customer_connect',
                    'subscription_package_id' => $package->id,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ];

                if ($hasUuid) {
                    $row['id'] = \Illuminate\Support\Str::uuid()->toString();
                }

                DB::table('subscription_package_features')->insert($row);
            }
        }

        // ── 3. Portal permissions ─────────────────────────────────────────────
        if (!Schema::hasTable('permissions')) {
            return;
        }

        // Determine the module id
        $moduleId = null;
        if (Schema::hasTable('modules')) {
            $module = DB::table('modules')->where('module_name', 'customerconnect')->first();
            $moduleId = $module?->id;
        }

        $permCols = Schema::getColumnListing('permissions');

        $portalPermissions = [
            'access_customer_portal' => 'Access Customer Portal',
            'rebook_online'          => 'Rebook Online (Portal)',
            'view_own_invoices'      => 'View Own Invoices (Portal)',
            'manage_own_properties'  => 'Manage Own Properties (Portal)',
        ];

        foreach ($portalPermissions as $name => $displayName) {
            $row = [];

            if (in_array('name', $permCols, true)) {
                $row['name'] = $name;
            }
            if (in_array('display_name', $permCols, true)) {
                $row['display_name'] = $displayName;
            }
            if (in_array('guard_name', $permCols, true)) {
                $row['guard_name'] = 'web';
            }
            if (in_array('module_id', $permCols, true) && $moduleId) {
                $row['module_id'] = $moduleId;
            }
            if (in_array('created_at', $permCols, true)) {
                $row['created_at'] = now();
            }
            if (in_array('updated_at', $permCols, true)) {
                $row['updated_at'] = now();
            }

            if (!empty($row) && array_key_exists('name', $row)) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $name],
                    $row
                );
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subscription_package_features')) {
            DB::table('subscription_package_features')
                ->where('feature', 'customer_connect')
                ->delete();
        }

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->whereIn('name', [
                    'access_customer_portal',
                    'rebook_online',
                    'view_own_invoices',
                    'manage_own_properties',
                ])
                ->delete();
        }
    }
};
