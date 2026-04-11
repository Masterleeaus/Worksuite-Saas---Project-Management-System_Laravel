<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends the existing `discounts` table with FSM / cleaning-business columns.
 *
 * Does NOT create a new promotions table — adds to the existing one.
 * Rollback removes only the added columns.
 */
return new class extends Migration {

    private array $columns = [
        'promo_type',
        'applies_to',
        'service_type_filter',
        'zone_filter',
        'new_clients_only',
        'min_bookings_required',
        'max_uses_per_client',
        'total_uses',
        'max_total_uses',
        'referral_code',
        'auto_apply',
        'campaign_name',
    ];

    public function up(): void
    {
        if (!Schema::hasTable('discounts')) {
            return;
        }

        Schema::table('discounts', function (Blueprint $table) {
            if (!Schema::hasColumn('discounts', 'promo_type')) {
                $table->string('promo_type')->default('percentage')
                    ->comment('percentage | fixed | free_service | bundle');
            }
            if (!Schema::hasColumn('discounts', 'applies_to')) {
                $table->string('applies_to')->default('all')
                    ->comment('all | service_type | zone | new_client | recurring');
            }
            if (!Schema::hasColumn('discounts', 'service_type_filter')) {
                $table->string('service_type_filter')->nullable()
                    ->comment('e.g. deep_clean, regular');
            }
            if (!Schema::hasColumn('discounts', 'zone_filter')) {
                $table->string('zone_filter')->nullable()
                    ->comment('suburb/zone restriction');
            }
            if (!Schema::hasColumn('discounts', 'new_clients_only')) {
                $table->boolean('new_clients_only')->default(false);
            }
            if (!Schema::hasColumn('discounts', 'min_bookings_required')) {
                $table->integer('min_bookings_required')->default(0)
                    ->comment('loyalty: N+ prior bookings required');
            }
            if (!Schema::hasColumn('discounts', 'max_uses_per_client')) {
                $table->integer('max_uses_per_client')->default(1);
            }
            if (!Schema::hasColumn('discounts', 'total_uses')) {
                $table->integer('total_uses')->default(0)
                    ->comment('running redemption counter');
            }
            if (!Schema::hasColumn('discounts', 'max_total_uses')) {
                $table->integer('max_total_uses')->nullable()
                    ->comment('global cap; null = unlimited');
            }
            if (!Schema::hasColumn('discounts', 'referral_code')) {
                $table->string('referral_code')->nullable()
                    ->comment('referral-specific promo code');
            }
            if (!Schema::hasColumn('discounts', 'auto_apply')) {
                $table->boolean('auto_apply')->default(false)
                    ->comment('apply automatically without coupon code');
            }
            if (!Schema::hasColumn('discounts', 'campaign_name')) {
                $table->string('campaign_name')->nullable()
                    ->comment('for analytics grouping');
            }
        });

        // Unique index: one referral_code per company.
        // In MySQL (and most RDBMS), a composite UNIQUE index treats NULL values as
        // distinct from one another, so multiple rows with NULL referral_code for the
        // same company_id are permitted without violating this constraint.
        if (!$this->indexExists('discounts', 'discounts_referral_code_company_id_unique')) {
            Schema::table('discounts', function (Blueprint $table) {
                $table->unique(['referral_code', 'company_id'], 'discounts_referral_code_company_id_unique');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('discounts')) {
            return;
        }

        // Drop the composite unique index first
        try {
            Schema::table('discounts', function (Blueprint $table) {
                $table->dropUnique('discounts_referral_code_company_id_unique');
            });
        } catch (\Throwable) {
            // Index may not exist; safe to ignore
        }

        $existingColumns = array_filter(
            $this->columns,
            fn (string $col) => Schema::hasColumn('discounts', $col)
        );

        if ($existingColumns) {
            Schema::table('discounts', function (Blueprint $table) use ($existingColumns) {
                $table->dropColumn(array_values($existingColumns));
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = \Illuminate\Support\Facades\DB::select(
                "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
                [$indexName]
            );
            return count($indexes) > 0;
        } catch (\Throwable) {
            return false;
        }
    }
};
