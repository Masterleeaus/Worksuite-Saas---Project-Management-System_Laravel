<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Seeds `promotion_management` as a subscription-package feature for all
 * existing packages so that companies can gate this module via their plan.
 *
 * Feature key: promotion_management
 *   – basic coupon codes available in standard plans
 *   – auto-apply, bundles, referral system in premium plans
 */
return new class extends Migration {

    private string $feature = 'promotion_management';

    public function up(): void
    {
        if (!Schema::hasTable('subscription_packages') || !Schema::hasTable('subscription_package_features')) {
            return;
        }

        $cols       = Schema::getColumnListing('subscription_package_features');
        $hasFeature = in_array('feature', $cols, true);
        $hasPkgId   = in_array('subscription_package_id', $cols, true);

        if (!$hasFeature || !$hasPkgId) {
            return;
        }

        $packages = DB::table('subscription_packages')->pluck('id');

        foreach ($packages as $packageId) {
            $exists = DB::table('subscription_package_features')
                ->where('subscription_package_id', $packageId)
                ->where('feature', $this->feature)
                ->exists();

            if (!$exists) {
                $row = [
                    'subscription_package_id' => $packageId,
                    'feature'                 => $this->feature,
                    'company_id'              => null,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ];

                if (in_array('id', $cols, true)) {
                    $row['id'] = (string) Str::uuid();
                }

                DB::table('subscription_package_features')->insert($row);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_package_features')) {
            return;
        }

        DB::table('subscription_package_features')
            ->where('feature', $this->feature)
            ->delete();
    }
};
