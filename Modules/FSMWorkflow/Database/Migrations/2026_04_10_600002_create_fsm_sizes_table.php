<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Part 3 – FSM Job Sizes
 * Admin-configurable size tiers (XS/S/M/L/XL) attached to FSM Orders.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_sizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('code', 8);  // e.g. XS, S, M, L, XL
            $table->string('name', 128);
            $table->text('description')->nullable();
            $table->unsignedInteger('sequence')->default(0)->index();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_sizes');
    }
};
