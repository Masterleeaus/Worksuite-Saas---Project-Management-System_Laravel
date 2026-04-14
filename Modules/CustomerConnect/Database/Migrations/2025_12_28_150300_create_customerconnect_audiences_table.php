<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customerconnect_audiences')) {
            return;
        }
        Schema::create('customerconnect_audiences', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedInteger('company_id')->nullable()->index();
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('source')->default('manual')->index(); // manual|customers|leads|import
    $table->json('filters')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('customerconnect_audiences');
    }
};
