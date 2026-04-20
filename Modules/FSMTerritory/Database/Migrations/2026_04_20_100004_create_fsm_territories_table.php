<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_territories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name', 256);
            $table->string('description', 512)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('type', 32)->nullable()->default('zip');
            $table->text('zip_codes')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('fsm_branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_territories');
    }
};
