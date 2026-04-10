<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Skills assigned to individual workers/cleaners
        Schema::create('fsm_employee_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();           // the cleaner/worker
            $table->unsignedBigInteger('skill_id')->index();
            $table->unsignedBigInteger('skill_level_id')->nullable()->index();
            $table->date('expiry_date')->nullable();                  // certification expiry
            $table->string('certificate_path', 512)->nullable();      // uploaded certificate document
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('skill_id')->references('id')->on('fsm_skills')->cascadeOnDelete();
            $table->foreign('skill_level_id')->references('id')->on('fsm_skill_levels')->nullOnDelete();
            // No FK on user_id — user table may vary; handled in application layer
            $table->unique(['user_id', 'skill_id'], 'fsm_employee_skill_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_employee_skills');
    }
};
