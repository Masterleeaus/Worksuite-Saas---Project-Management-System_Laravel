<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_skill_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->string('name', 128);          // e.g. Competent, Certified, Expert
            $table->unsignedTinyInteger('progress')->default(0); // 0-100 proficiency %
            $table->boolean('default_level')->default(false);
            $table->timestamps();

            $table->foreign('skill_id')->references('id')->on('fsm_skills')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_skill_levels');
    }
};
