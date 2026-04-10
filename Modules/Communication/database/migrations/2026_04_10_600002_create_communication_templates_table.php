<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Communication Templates table — stores reusable message templates with
 * variable substitution support ({customer_name}, {booking_date}, etc.).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communication_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            $table->string('name', 255);

            // Channel this template applies to: email | sms | chat | push | all
            $table->string('type', 30)->default('email')->index();

            $table->string('subject', 512)->nullable();

            $table->longText('body');

            // JSON array of variable names: ["customer_name","booking_date","cleaner_name"]
            $table->json('variables')->nullable();

            // active | inactive
            $table->string('status', 20)->default('active')->index();

            // Pre-built slug for seeded templates (booking_confirmation, reminder, etc.)
            $table->string('slug', 100)->nullable()->unique();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_templates');
    }
};
