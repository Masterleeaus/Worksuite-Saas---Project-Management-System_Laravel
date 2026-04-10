<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_message_intents')) {
            return;
        }
        Schema::create('customerconnect_message_intents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('thread_id')->index();
            $table->unsignedBigInteger('message_id')->nullable()->index();
            $table->string('intent_key', 191)->index();
            $table->unsignedSmallInteger('confidence')->default(0);
            $table->json('payload')->nullable();
            $table->timestamps();
            $table->index(['company_id','user_id','thread_id'], 'cc_intents_tenant_thread_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_message_intents');
    }
};
