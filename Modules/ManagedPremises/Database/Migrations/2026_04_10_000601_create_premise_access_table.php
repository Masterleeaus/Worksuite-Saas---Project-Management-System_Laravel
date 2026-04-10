<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('premise_access')) {
            return;
        }

        Schema::create('premise_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('premise_id')->index();

            // Key & lock codes (secure storage)
            $table->string('key_code', 120)->nullable();
            $table->string('alarm_code', 120)->nullable();
            $table->string('gate_code', 120)->nullable();
            $table->string('intercom_code', 120)->nullable();

            // Access & parking details
            $table->text('parking_info')->nullable();
            $table->text('special_notes')->nullable();

            // Pet information for cleaners/tradies
            $table->text('pet_info')->nullable();

            // Key holding: 'office_holds' | 'customer_provides' | 'lockbox' | 'other'
            $table->string('key_holding_status', 40)->nullable();

            // Strata / building access
            $table->text('building_access_notes')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'premise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premise_access');
    }
};
