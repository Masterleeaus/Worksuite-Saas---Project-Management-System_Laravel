<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_campaign_runs')) {
            return;
        }
        Schema::create('customerconnect_campaign_runs', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('campaign_id')->index();
    $table->unsignedBigInteger('audience_id')->nullable()->index();
    $table->unsignedInteger('company_id')->nullable()->index();
    $table->string('status')->default('queued')->index(); // queued|running|completed|failed|cancelled
    $table->timestamp('scheduled_at')->nullable()->index();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('finished_at')->nullable();
    $table->json('meta')->nullable();
    $table->timestamps();
    $table->foreign('campaign_id')->references('id')->on('customerconnect_campaigns')->onDelete('cascade');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_campaign_runs');
    }
};
