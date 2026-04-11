<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispatch_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 64)->unique();
            $table->string('job_type', 16)->default('JOB'); // JOB / ABSENCE
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('planning_status', 1)->default('U'); // PlanningStatus enum value
            $table->string('life_cycle_status', 16)->default('created'); // LifeCycleStatus enum value
            $table->boolean('auto_planning')->default(true);
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->datetime('requested_start_datetime')->nullable();
            $table->float('requested_duration_minutes')->default(60);
            $table->datetime('scheduled_start_datetime')->nullable();
            $table->float('scheduled_duration_minutes')->nullable();
            $table->unsignedBigInteger('requested_primary_worker_id')->nullable()->index();
            $table->unsignedBigInteger('scheduled_primary_worker_id')->nullable()->index();
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->json('flex_form_data')->nullable();
            $table->unsignedBigInteger('worksuite_project_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('dispatch_teams')->nullOnDelete();
            $table->foreign('location_id')->references('id')->on('dispatch_locations')->nullOnDelete();
            $table->foreign('requested_primary_worker_id')->references('id')->on('dispatch_workers')->nullOnDelete();
            $table->foreign('scheduled_primary_worker_id')->references('id')->on('dispatch_workers')->nullOnDelete();
        });

        Schema::create('dispatch_job_secondary_workers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('worker_id');

            $table->foreign('job_id')->references('id')->on('dispatch_jobs')->cascadeOnDelete();
            $table->foreign('worker_id')->references('id')->on('dispatch_workers')->cascadeOnDelete();

            $table->unique(['job_id', 'worker_id']);
        });

        Schema::create('dispatch_job_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_id');
            $table->string('tag', 64);

            $table->foreign('job_id')->references('id')->on('dispatch_jobs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_job_tags');
        Schema::dropIfExists('dispatch_job_secondary_workers');
        Schema::dropIfExists('dispatch_jobs');
    }
};
