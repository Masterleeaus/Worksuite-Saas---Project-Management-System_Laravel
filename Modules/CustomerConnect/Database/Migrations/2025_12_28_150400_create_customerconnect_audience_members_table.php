<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_audience_members')) {
            return;
        }
        Schema::create('customerconnect_audience_members', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('audience_id')->index();
    $table->unsignedInteger('company_id')->nullable()->index();
    $table->string('record_type')->default('customer')->index(); // customer|lead|user|custom
    $table->unsignedBigInteger('record_id')->nullable()->index();
    $table->string('name')->nullable();
    $table->string('email')->nullable()->index();
    $table->string('phone')->nullable()->index();
    $table->string('whatsapp')->nullable()->index();
    $table->string('telegram_chat_id')->nullable()->index();
    $table->timestamp('opt_in_email_at')->nullable();
    $table->timestamp('opt_in_sms_at')->nullable();
    $table->timestamp('opt_in_whatsapp_at')->nullable();
    $table->timestamp('opt_in_telegram_at')->nullable();
    $table->timestamps();
    $table->foreign('audience_id')->references('id')->on('customerconnect_audiences')->onDelete('cascade');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_audience_members');
    }
};
