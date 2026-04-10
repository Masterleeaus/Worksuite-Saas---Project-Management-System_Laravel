<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            if (!Schema::hasColumn('zones', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('zones', 'postcodes')) {
                $table->text('postcodes')->nullable()->after('description')
                    ->comment('Comma-separated postcode/suburb list for zone matching');
            }
            if (!Schema::hasColumn('zones', 'min_booking_value')) {
                $table->decimal('min_booking_value', 10, 2)->nullable()->after('postcodes')
                    ->comment('Minimum booking value required for this zone');
            }
            if (!Schema::hasColumn('zones', 'availability_window')) {
                $table->string('availability_window', 100)->nullable()->after('min_booking_value')
                    ->comment('e.g. Mon-Fri, weekdays, weekends');
            }
        });
    }

    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            if (Schema::hasColumn('zones', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('zones', 'postcodes')) {
                $table->dropColumn('postcodes');
            }
            if (Schema::hasColumn('zones', 'min_booking_value')) {
                $table->dropColumn('min_booking_value');
            }
            if (Schema::hasColumn('zones', 'availability_window')) {
                $table->dropColumn('availability_window');
            }
        });
    }
};
