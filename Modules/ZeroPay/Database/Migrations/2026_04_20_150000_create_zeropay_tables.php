<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zeropay_sessions')) {
            Schema::create('zeropay_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->unsignedInteger('invoice_id')->nullable()->index();
                $table->unsignedInteger('client_id')->nullable()->index();
                $table->unsignedBigInteger('booking_id')->nullable()->index();
                $table->string('source_type', 50)->default('invoice');
                $table->string('source_reference')->nullable();
                $table->string('public_token', 80)->unique();
                $table->string('status', 40)->default('draft')->index();
                $table->string('selected_method', 60)->nullable();
                $table->decimal('amount', 12, 2)->default(0);
                $table->unsignedInteger('currency_id')->nullable();
                $table->string('payment_link')->nullable();
                $table->text('qr_payload')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->unsignedInteger('created_by')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_attempts')) {
            Schema::create('zeropay_attempts', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->constrained('zeropay_sessions')->cascadeOnDelete();
                $table->string('method', 60)->index();
                $table->string('rail_type', 30)->default('zero_fee');
                $table->string('status', 40)->default('initiated')->index();
                $table->string('external_reference')->nullable()->index();
                $table->decimal('amount', 12, 2)->default(0);
                $table->decimal('fee_amount', 12, 2)->default(0);
                $table->unsignedInteger('processed_payment_id')->nullable()->index();
                $table->timestamp('confirmed_at')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_download_tokens')) {
            Schema::create('zeropay_download_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->nullable()->constrained('zeropay_sessions')->nullOnDelete();
                $table->unsignedInteger('invoice_id')->nullable()->index();
                $table->unsignedInteger('payment_id')->nullable()->index();
                $table->string('type', 30)->index();
                $table->string('token', 80)->unique();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamp('used_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_bank_matches')) {
            Schema::create('zeropay_bank_matches', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->nullable()->constrained('zeropay_sessions')->nullOnDelete();
                $table->foreignId('attempt_id')->nullable()->constrained('zeropay_attempts')->nullOnDelete();
                $table->unsignedInteger('payment_id')->nullable()->index();
                $table->string('bank_reference')->nullable()->index();
                $table->decimal('suggested_amount', 12, 2)->default(0);
                $table->decimal('confidence_score', 5, 2)->default(0);
                $table->string('status', 40)->default('queued')->index();
                $table->timestamp('matched_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_followups')) {
            Schema::create('zeropay_followups', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->nullable()->constrained('zeropay_sessions')->nullOnDelete();
                $table->foreignId('attempt_id')->nullable()->constrained('zeropay_attempts')->nullOnDelete();
                $table->string('channel', 40)->index();
                $table->string('status', 40)->default('queued')->index();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->text('message')->nullable();
                $table->boolean('ai_suggested')->default(false);
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_channel_logs')) {
            Schema::create('zeropay_channel_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->nullable()->constrained('zeropay_sessions')->nullOnDelete();
                $table->foreignId('followup_id')->nullable()->constrained('zeropay_followups')->nullOnDelete();
                $table->string('direction', 20)->default('outbound');
                $table->string('channel', 40)->index();
                $table->string('status', 40)->default('queued')->index();
                $table->string('subject')->nullable();
                $table->text('body')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_voice_runs')) {
            Schema::create('zeropay_voice_runs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->nullable()->constrained('zeropay_sessions')->nullOnDelete();
                $table->foreignId('followup_id')->nullable()->constrained('zeropay_followups')->nullOnDelete();
                $table->string('provider', 40)->default('twilio');
                $table->string('status', 40)->default('queued')->index();
                $table->string('call_to')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->longText('transcript')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_ai_suggestions')) {
            Schema::create('zeropay_ai_suggestions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->index();
                $table->foreignId('session_id')->nullable()->constrained('zeropay_sessions')->nullOnDelete();
                $table->foreignId('followup_id')->nullable()->constrained('zeropay_followups')->nullOnDelete();
                $table->foreignId('bank_match_id')->nullable()->constrained('zeropay_bank_matches')->nullOnDelete();
                $table->string('suggestion_type', 50)->index();
                $table->text('suggestion');
                $table->decimal('score', 5, 2)->default(0);
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('dismissed_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zeropay_settings')) {
            Schema::create('zeropay_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->unique();
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zeropay_settings');
        Schema::dropIfExists('zeropay_ai_suggestions');
        Schema::dropIfExists('zeropay_voice_runs');
        Schema::dropIfExists('zeropay_channel_logs');
        Schema::dropIfExists('zeropay_followups');
        Schema::dropIfExists('zeropay_bank_matches');
        Schema::dropIfExists('zeropay_download_tokens');
        Schema::dropIfExists('zeropay_attempts');
        Schema::dropIfExists('zeropay_sessions');
    }
};
