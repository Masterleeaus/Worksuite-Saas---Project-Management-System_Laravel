<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tr_workpermits')) {
            return;
        }

        Schema::create('tr_workpermits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('contractor_name');
            $table->string('company_name')->nullable();
            $table->text('work_description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('approved_bm')->nullable();
            $table->dateTime('approved_bm_at')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->timestamps();

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('validated_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
            }
        });

        // Add task_id FK after table creation so we can safely guard on tasks table existence
        if (Schema::hasTable('tasks') && Schema::hasColumn('tr_workpermits', 'task_id')) {
            Schema::table('tr_workpermits', function (Blueprint $table) {
                $table->foreign('task_id')->references('id')->on('tasks')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_workpermits');
    }
};
