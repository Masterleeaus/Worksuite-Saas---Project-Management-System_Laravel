<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Per-site / per-service pricing lines under an agreement
        Schema::create('fsm_agreement_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('agreement_id')->index();
            $table->unsignedBigInteger('location_id')->nullable()->index(); // specific site (null = all sites)
            $table->string('service_description', 256);
            $table->string('frequency', 64)->nullable(); // e.g. "fortnightly", "monthly"
            $table->decimal('unit_price', 14, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('agreement_id')
                ->references('id')->on('fsm_service_agreements')->cascadeOnDelete();
            $table->foreign('location_id')
                ->references('id')->on('fsm_locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_agreement_lines');
    }
};
