<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Add cleaning-business-specific columns to the services table.
     * Columns: duration_minutes, base_price, frequency, eco_friendly, zone_id, image_icon
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'duration_minutes')) {
                $table->unsignedInteger('duration_minutes')->nullable()->after('description');
            }
            if (! Schema::hasColumn('services', 'base_price')) {
                $table->decimal('base_price', 10, 2)->nullable()->after('duration_minutes');
            }
            if (! Schema::hasColumn('services', 'frequency')) {
                $table->string('frequency', 50)->nullable()->after('base_price');
            }
            if (! Schema::hasColumn('services', 'eco_friendly')) {
                $table->boolean('eco_friendly')->default(false)->after('frequency');
            }
            if (! Schema::hasColumn('services', 'zone_id')) {
                $table->string('zone_id', 36)->nullable()->after('eco_friendly');
            }
            if (! Schema::hasColumn('services', 'image_icon')) {
                $table->string('image_icon', 191)->nullable()->after('zone_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('services', 'duration_minutes') ? 'duration_minutes' : null,
                Schema::hasColumn('services', 'base_price') ? 'base_price' : null,
                Schema::hasColumn('services', 'frequency') ? 'frequency' : null,
                Schema::hasColumn('services', 'eco_friendly') ? 'eco_friendly' : null,
                Schema::hasColumn('services', 'zone_id') ? 'zone_id' : null,
                Schema::hasColumn('services', 'image_icon') ? 'image_icon' : null,
            ]));
        });
    }
};
