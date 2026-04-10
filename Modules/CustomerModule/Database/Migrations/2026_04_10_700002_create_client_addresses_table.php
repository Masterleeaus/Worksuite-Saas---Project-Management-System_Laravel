<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Multiple addresses / properties per client.
     * Linked to the core users table (client role) via client_id.
     */
    public function up(): void
    {
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('label', 100)->nullable();         // e.g. "Home", "Investment Property"
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            $table->string('suburb', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('property_type', 50)->nullable(); // e.g. "house", "apartment", "office"
            $table->text('special_instructions')->nullable(); // per-property notes
            $table->string('pet_info', 255)->nullable();      // per-property pet / allergy info
            $table->boolean('is_primary')->default(false);
            $table->boolean('key_holding')->default(false);
            $table->text('alarm_code')->nullable();           // encrypted
            $table->string('access_notes', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_addresses');
    }
};
