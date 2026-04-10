<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Create the service_pricing_rules table.
     * Stores zone/category-based pricing overrides with per-bedroom and per-bathroom pricing.
     */
    public function up(): void
    {
        Schema::create('service_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('service_id', 36)->index();
            $table->string('zone_id', 36)->nullable()->index()->comment('NULL means applies to all zones');
            $table->string('label', 191)->nullable()->comment('Human-readable description of this rule');
            $table->decimal('base_price_override', 10, 2)->nullable()->comment('Overrides service base_price for this zone');
            $table->decimal('per_bedroom_price', 10, 2)->nullable()->default(0);
            $table->decimal('per_bathroom_price', 10, 2)->nullable()->default(0);
            $table->decimal('min_price', 10, 2)->nullable()->comment('Minimum total price floor');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pricing_rules');
    }
};
