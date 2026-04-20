<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_warranty_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name', 256);
            $table->string('equipment_category', 256)->nullable();
            $table->unsignedInteger('warranty_duration')->default(12);
            $table->string('warranty_type', 16)->default('month');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_warranty_profiles');
    }
};
