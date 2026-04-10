<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('to_number')->nullable();
            $table->string('channel', 20)->default('sms'); // sms, whatsapp
            $table->string('trigger_type', 60)->nullable(); // slug value
            $table->text('message')->nullable();
            $table->string('status', 20)->default('pending'); // pending, delivered, failed
            $table->string('twilio_sid', 80)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_notification_logs');
    }
};
