<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_campaigns')) {
            return;
        }
        Schema::create('customerconnect_campaigns', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedInteger('company_id')->nullable()->index();
    $table->string('name');
    $table->string('status')->default('draft')->index(); // draft|active|paused|archived
    $table->text('description')->nullable();
    $table->json('settings')->nullable();
    $table->unsignedBigInteger('created_by')->nullable()->index();
    $table->unsignedBigInteger('updated_by')->nullable()->index();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_campaigns');
    }
};
