<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('skill_type_id')->nullable()->index();
            $table->string('name', 128);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('skill_type_id')->references('id')->on('fsm_skill_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_skills');
    }
};
