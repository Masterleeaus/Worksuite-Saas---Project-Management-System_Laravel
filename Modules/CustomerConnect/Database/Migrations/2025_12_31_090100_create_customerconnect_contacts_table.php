<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_contacts')) {
            return;
        }
        Schema::create('customerconnect_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable()->index();

            // Link to a core record if available (client/user/lead/etc.)
            $table->string('source_type')->nullable()->index(); // e.g. client|user|lead|unknown
            $table->unsignedBigInteger('source_id')->nullable()->index();

            $table->string('display_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone_e164')->nullable()->index();
            $table->string('whatsapp_e164')->nullable()->index();
            $table->string('telegram_chat_id')->nullable()->index();

            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_contacts');
    }
};
