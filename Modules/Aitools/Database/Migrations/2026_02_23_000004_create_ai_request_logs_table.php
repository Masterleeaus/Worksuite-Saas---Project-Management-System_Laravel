<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_request_logs')) {
            return;
        }

        Schema::create('ai_request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('provider_id')->nullable()->index();
            $table->unsignedBigInteger('model_id')->nullable()->index();
            $table->string('operation')->nullable();
            $table->string('status')->default('ok'); // ok|error
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();
            $table->text('prompt_excerpt')->nullable();
            $table->text('response_excerpt')->nullable();
            $table->integer('latency_ms')->nullable();
            $table->json('request_meta')->nullable();
            $table->json('response_meta')->nullable();
            $table->string('request_hash', 64)->nullable()->index();
            $table->timestamps();

            $table->index(['company_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_request_logs');
    }
};
