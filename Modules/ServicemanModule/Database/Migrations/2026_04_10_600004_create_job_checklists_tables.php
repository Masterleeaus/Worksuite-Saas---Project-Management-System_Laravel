<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('task_id')->index();
            $table->string('title');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
        });

        Schema::create('job_checklist_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_checklist_id')->index();
            $table->string('label');
            $table->boolean('is_completed')->default(false);
            $table->unsignedBigInteger('completed_by')->nullable()->index();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('job_checklist_id')->references('id')->on('job_checklists')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_checklist_items');
        Schema::dropIfExists('job_checklists');
    }
};
