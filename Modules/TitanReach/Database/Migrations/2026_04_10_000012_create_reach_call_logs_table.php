<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reach_call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('call_campaign_id')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->nullable()->index();
            $table->unsignedBigInteger('conversation_id')->nullable()->index();
            $table->string('call_sid')->nullable()->index();
            $table->enum('direction', ['inbound', 'outbound'])->index();
            $table->string('from_number');
            $table->string('to_number');
            $table->string('status')->default('initiated')->index(); // initiated, ringing, answered, completed, no-answer, busy, voicemail, opted-out
            $table->integer('duration')->nullable(); // seconds
            $table->string('recording_url')->nullable();
            $table->text('transcript')->nullable();
            $table->string('keypress')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reach_call_logs');
    }
};
