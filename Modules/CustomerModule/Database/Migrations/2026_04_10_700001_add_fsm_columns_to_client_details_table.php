<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add FSM / cleaning-specific overlay columns to the core client_details table.
     * This does NOT create a new table — it extends the existing Worksuite core table.
     */
    public function up(): void
    {
        Schema::table('client_details', function (Blueprint $table) {
            if (! Schema::hasColumn('client_details', 'preferred_cleaner_id')) {
                $table->unsignedBigInteger('preferred_cleaner_id')->nullable()->after('sub_category_id');
            }

            if (! Schema::hasColumn('client_details', 'key_holding')) {
                $table->boolean('key_holding')->default(false)->after('preferred_cleaner_id');
            }

            if (! Schema::hasColumn('client_details', 'pet_info')) {
                $table->string('pet_info', 255)->nullable()->after('key_holding');
            }

            if (! Schema::hasColumn('client_details', 'alarm_code')) {
                // Stored encrypted — see ClientDetails model cast
                $table->text('alarm_code')->nullable()->after('pet_info');
            }

            if (! Schema::hasColumn('client_details', 'access_notes')) {
                $table->string('access_notes', 500)->nullable()->after('alarm_code');
            }

            if (! Schema::hasColumn('client_details', 'client_tag')) {
                $table->enum('client_tag', ['residential', 'commercial', 'strata', 'airbnb', 'vip'])
                    ->nullable()
                    ->after('access_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('client_details', function (Blueprint $table) {
            foreach (['preferred_cleaner_id', 'key_holding', 'pet_info', 'alarm_code', 'access_notes', 'client_tag'] as $column) {
                if (Schema::hasColumn('client_details', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
