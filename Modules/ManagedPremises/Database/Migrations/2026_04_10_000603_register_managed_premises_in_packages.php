<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Register managed_premises as a module in the modules table (SA module list)
        if (Schema::hasTable('modules')) {
            $exists = DB::table('modules')->where('module_name', 'managedpremises')->exists();
            if (!$exists) {
                DB::table('modules')->insert([
                    'module_name' => 'managedpremises',
                    'description' => 'Manage client premises — access details, photos, special requirements',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            } else {
                DB::table('modules')->where('module_name', 'managedpremises')->update([
                    'description' => 'Manage client premises — access details, photos, special requirements',
                    'updated_at'  => now(),
                ]);
            }
        }

        // Register managed_premises as a feature in subscription_package_features
        // so it appears in SA packages management and can be assigned to plans
        if (!Schema::hasTable('subscription_package_features')) {
            return;
        }

        $cols = Schema::getColumnListing('subscription_package_features');

        // Check for uuid primary key
        $hasUuid = in_array('id', $cols, true);
        $hasPkgId = in_array('subscription_package_id', $cols, true);
        $hasFeature = in_array('feature', $cols, true);

        if (!$hasFeature) {
            return;
        }

        // Get all subscription packages
        if (!Schema::hasTable('subscription_packages')) {
            return;
        }

        $packages = DB::table('subscription_packages')->get();

        foreach ($packages as $package) {
            $existing = DB::table('subscription_package_features')
                ->where('subscription_package_id', $package->id)
                ->where('feature', 'managed_premises')
                ->exists();

            if (!$existing) {
                $row = [
                    'feature'                 => 'managed_premises',
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
    }

    public function down(): void
    {
        if (Schema::hasTable('subscription_package_features')) {
            DB::table('subscription_package_features')
                ->where('feature', 'managed_premises')
                ->delete();
        }
    }
};
