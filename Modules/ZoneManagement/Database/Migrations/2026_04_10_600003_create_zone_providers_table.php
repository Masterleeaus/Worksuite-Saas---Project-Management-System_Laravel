<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zone_providers')) {
            Schema::create('zone_providers', function (Blueprint $table) {
                $table->id();
                $table->uuid('zone_id')->index();
                $table->unsignedBigInteger('provider_id')->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();

                $table->unique(['zone_id', 'provider_id']);
                $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_providers');
    }
};
