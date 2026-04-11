<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_campaign_steps')) {
            return;
        }
        Schema::create('customerconnect_campaign_steps', function (Blueprint $table) {
    $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
    $table->unsignedBigInteger('campaign_id')->index();
    $table->unsignedInteger('position')->default(1)->index();
    $table->string('type')->default('send')->index(); // send|wait|condition|stop
    $table->string('channel')->nullable()->index(); // email|sms|whatsapp|telegram
    $table->unsignedInteger('delay_minutes')->default(0);
    $table->string('subject')->nullable();
    $table->longText('body')->nullable();
    $table->json('meta')->nullable();
    $table->timestamps();
    $table->foreign('campaign_id')->references('id')->on('customerconnect_campaigns')->onDelete('cascade');});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_campaign_steps');
    }
};
