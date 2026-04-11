<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Register 'field_items' as a subscription package feature.
     * Safe to run even if subscription tables do not yet exist.
     */
    public function up(): void
    {
        if (!Schema::hasTable('subscription_packages') || !Schema::hasTable('subscription_package_features')) {
            return;
        }

        $packages = DB::table('subscription_packages')->pluck('id');

        foreach ($packages as $packageId) {
            $alreadyExists = DB::table('subscription_package_features')
                ->where('subscription_package_id', $packageId)
                ->where('feature', 'field_items')
                ->exists();

            if (!$alreadyExists) {
                DB::table('subscription_package_features')->insert([
                    'id'                      => (string) Str::uuid(),
                    'subscription_package_id' => $packageId,
                    'feature'                 => 'field_items',
                    'company_id'              => null,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('subscription_package_features')) {
            return;
        }

        DB::table('subscription_package_features')
            ->where('feature', 'field_items')
            ->delete();
    }
};
