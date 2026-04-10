<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    public function up(): void
    {
        if (!Schema::hasTable('breach_reports')) {
            Schema::create('breach_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('title');
                $table->text('description');
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['open', 'investigating', 'notified', 'resolved'])->default('open');
                $table->timestamp('breach_detected_at');
                $table->timestamp('notification_deadline')->nullable();
                $table->timestamp('notified_at')->nullable();
                $table->integer('affected_users_count')->default(0);
                $table->text('affected_data_types')->nullable();
                $table->text('remediation_steps')->nullable();
                $table->unsignedBigInteger('reported_by')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('breach_reports');
    }

};
