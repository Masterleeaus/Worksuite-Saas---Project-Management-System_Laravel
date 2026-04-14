<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_talk_rooms')) {
            Schema::create('titan_talk_rooms', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->string('slug')->nullable()->index();
                $table->text('description')->nullable();
                $table->enum('type', [
                    'public', 'private', 'dm', 'team', 'department',
                    'project', 'booking', 'service_job', 'site',
                    'worker_team', 'issue', 'private_group',
                    'company', 'office', 'dispatch', 'finance', 'sales'
                ])->default('public');
                $table->unsignedInteger('created_by')->nullable()->index();
                $table->string('avatar')->nullable();
                $table->boolean('is_archived')->default(false);
                $table->unsignedBigInteger('reference_id')->nullable()->comment('Polymorphic: booking_id, job_id, project_id, etc.');
                $table->string('reference_type')->nullable()->comment('E.g. App\\Models\\Task, booking, etc.');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_talk_rooms');
    }
};
