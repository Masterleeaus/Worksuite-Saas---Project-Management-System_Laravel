<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_deliveries')) {
            return;
        }
        Schema::create('customerconnect_deliveries', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('run_id')->index();
    $table->unsignedBigInteger('step_id')->nullable()->index();
    $table->unsignedBigInteger('audience_member_id')->nullable()->index();
    $table->unsignedInteger('company_id')->nullable()->index();
    $table->string('channel')->index();
    $table->string('to')->nullable();
    $table->string('subject')->nullable();
    $table->longText('body')->nullable();
    $table->string('status')->default('queued')->index(); // pending|sent|failed|skipped
    $table->unsignedInteger('attempts')->default(0);
    $table->text('error')->nullable();
    $table->json('provider_response')->nullable();
    $table->timestamp('sent_at')->nullable()->index();
    $table->timestamps();
    $table->foreign('run_id')->references('id')->on('customerconnect_campaign_runs')->onDelete('cascade');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_deliveries');
    }
};
