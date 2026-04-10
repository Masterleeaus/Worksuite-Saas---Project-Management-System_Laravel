<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_territories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('name', 128);
            $table->string('type', 32)->default('territory'); // region, district, branch, territory
            $table->text('zip_codes')->nullable(); // comma-separated or JSON
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('fsm_territories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_territories');
    }
};
