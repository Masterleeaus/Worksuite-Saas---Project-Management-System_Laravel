<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispatch_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->datetime('started_at');
            $table->datetime('ended_at')->nullable();
            $table->text('description');
            $table->unsignedBigInteger('job_id')->nullable()->index();
            $table->unsignedBigInteger('worker_id')->nullable()->index();
            $table->string('source', 16)->default('SYSTEM'); // SYSTEM / MANUAL / PLANNER
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('dispatch_jobs')->nullOnDelete();
            $table->foreign('worker_id')->references('id')->on('dispatch_workers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_events');
    }
};
