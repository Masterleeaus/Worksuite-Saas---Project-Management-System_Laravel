<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Per-booking NPS/rating survey instances (one row per survey sent to a client)
        if (Schema::hasTable('nps_surveys')) {
            return;
        }
        Schema::create('nps_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->integer('nps_score')->nullable();          // 0-10
            $table->integer('service_rating')->nullable();     // 1-5 stars
            $table->integer('cleaner_rating')->nullable();     // 1-5 stars
            $table->integer('punctuality_rating')->nullable(); // 1-5 stars
            $table->text('comments')->nullable();
            $table->string('survey_token')->unique();          // UUID for public survey link
            $table->boolean('is_public')->default(false);      // show as testimonial
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['client_id']);
            $table->index(['booking_id']);
            $table->index(['survey_token']);
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // Feedback Insights (AI-generated per ticket)
        Schema::create('feedback_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('feedback_ticket_id')->constrained('feedback_tickets')->cascadeOnDelete();

            $table->enum('insight_type', ['sentiment', 'category', 'priority', 'action', 'trend']);
            $table->string('title');
            $table->longText('description');
            $table->float('confidence_score')->default(0.0);
            $table->longText('suggested_action')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->index(['feedback_ticket_id']);
            $table->index(['insight_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_insights');
        Schema::dropIfExists('nps_surveys');
    }
};
