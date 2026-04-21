<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('zones') && !Schema::hasColumn('zones', 'territory_id')) {
            Schema::table('zones', function (Blueprint $table) {
                $table->unsignedBigInteger('territory_id')->nullable()->after('company_id')->index();
            });

            if (Schema::hasTable('fsm_territories')) {
                try {
                    Schema::table('zones', function (Blueprint $table) {
                        $table->foreign('territory_id')->references('id')->on('fsm_territories')->nullOnDelete();
                    });
                } catch (\Throwable) {
                    // Keep migration non-destructive across mixed historical schemas.
                }
            }
        }

        if (Schema::hasTable('zone_providers')) {
            if (!Schema::hasColumn('zone_providers', 'company_id')) {
                Schema::table('zone_providers', function (Blueprint $table) {
                    $table->unsignedBigInteger('company_id')->nullable()->index();
                });
            }

            $driver = Schema::getConnection()->getDriverName();
            if ($driver !== 'sqlite' && Schema::hasColumn('zone_providers', 'provider_id')) {
                // Convert provider_id from legacy integer to UUID-compatible string.
                Schema::table('zone_providers', function (Blueprint $table) {
                    $table->string('provider_id', 36)->nullable()->change();
                });

                // Null out rows that do not resolve to canonical providers.id UUIDs.
                if (Schema::hasTable('providers')) {
                    DB::statement("
                        UPDATE zone_providers zp
                        LEFT JOIN providers p ON p.id = zp.provider_id
                        SET zp.provider_id = NULL
                        WHERE zp.provider_id IS NOT NULL AND p.id IS NULL
                    ");
                }
            }
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive compatibility migration.
    }
};

