<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_ai_suggestions')) {
            return;
        }
        Schema::create('customerconnect_ai_suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('thread_id')->index();
            $table->unsignedBigInteger('message_id')->nullable()->index();
            $table->enum('type', ['reply','action'])->index();
            $table->text('title')->nullable();
            $table->longText('suggestion')->nullable();
            $table->json('payload')->nullable();
            $table->enum('status', ['draft','applied','dismissed'])->default('draft')->index();
            $table->timestamps();
            $table->index(['company_id','user_id','thread_id'], 'cc_suggest_tenant_thread_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_ai_suggestions');
    }
};
