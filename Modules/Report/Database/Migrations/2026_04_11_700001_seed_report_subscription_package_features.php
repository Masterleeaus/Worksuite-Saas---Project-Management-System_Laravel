<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Seed `basic_reports` and `advanced_reports` as subscription package features.
 *
 * - basic_reports    → all packages
 * - advanced_reports → premium packages only (packages where monthly_price > 0 or name contains 'premium'/'pro')
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('subscription_packages') || !Schema::hasTable('subscription_package_features')) {
            return;
        }

        $packages = DB::table('subscription_packages')->get();

        foreach ($packages as $package) {
            $this->seedFeature($package->id, 'basic_reports');

            // Advanced reports for paid/premium plans.
            $isPremium = (isset($package->monthly_price) && $package->monthly_price > 0)
                || (isset($package->name) && preg_match('/premium|pro|business|enterprise/i', $package->name));

            if ($isPremium) {
                $this->seedFeature($package->id, 'advanced_reports');
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_package_features')) {
            return;
        }

        DB::table('subscription_package_features')
            ->whereIn('feature', ['basic_reports', 'advanced_reports'])
            ->delete();
    }

    private function seedFeature(int|string $packageId, string $feature): void
    {
        $exists = DB::table('subscription_package_features')
            ->where('subscription_package_id', $packageId)
            ->where('feature', $feature)
            ->exists();

        if (!$exists) {
            DB::table('subscription_package_features')->insert([
                'id'                      => (string) Str::uuid(),
                'subscription_package_id' => $packageId,
                'feature'                 => $feature,
                'company_id'              => null,
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);
        }
    }
};
