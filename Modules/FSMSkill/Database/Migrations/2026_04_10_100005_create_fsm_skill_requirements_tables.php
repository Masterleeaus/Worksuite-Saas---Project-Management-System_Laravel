<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Skill requirements attached to FSM Orders
        Schema::create('fsm_order_skill_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->unsignedBigInteger('skill_level_id')->nullable()->index(); // minimum required level
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
            $table->foreign('skill_id')->references('id')->on('fsm_skills')->cascadeOnDelete();
            $table->foreign('skill_level_id')->references('id')->on('fsm_skill_levels')->nullOnDelete();
            $table->unique(['fsm_order_id', 'skill_id'], 'fsm_order_skill_req_unique');
        });

        // Skill requirements attached to FSM Templates
        Schema::create('fsm_template_skill_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fsm_template_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->unsignedBigInteger('skill_level_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('fsm_template_id')->references('id')->on('fsm_templates')->cascadeOnDelete();
            $table->foreign('skill_id')->references('id')->on('fsm_skills')->cascadeOnDelete();
            $table->foreign('skill_level_id')->references('id')->on('fsm_skill_levels')->nullOnDelete();
            $table->unique(['fsm_template_id', 'skill_id'], 'fsm_template_skill_req_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_template_skill_requirements');
        Schema::dropIfExists('fsm_order_skill_requirements');
    }
};
