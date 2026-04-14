<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customerconnect_channel_identities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('channel', 32)->index(); // sms|whatsapp|telegram|email
            $table->string('provider', 32)->nullable()->index();
            $table->string('inbound_address', 191)->index(); // e.g. +614..., whatsapp:+..., telegram:bot
            $table->json('meta_json')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'channel', 'inbound_address'], 'cc_identity_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_channel_identities');
    }
};
