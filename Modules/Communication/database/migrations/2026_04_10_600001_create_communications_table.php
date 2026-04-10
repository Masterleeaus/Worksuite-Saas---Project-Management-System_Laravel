<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Communications table — stores every sent/received message across all channels
 * (email, SMS, chat, push notification) for CleanSmartOS.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Channel: email | sms | chat | push
            $table->string('type', 30)->index();

            // Sender — either a user ID or a free-form address/number
            $table->unsignedBigInteger('from_user_id')->nullable()->index();
            $table->string('from_address', 255)->nullable();

            // Recipient — user ID plus the resolved address
            $table->unsignedBigInteger('to_user_id')->nullable()->index();
            $table->string('to_address', 255)->nullable();

            // Optional customer reference (CustomerModule)
            $table->unsignedBigInteger('customer_id')->nullable()->index();

            // Optional booking reference (BookingModule)
            $table->string('booking_id', 64)->nullable()->index();

            // Template that was used (if any)
            $table->unsignedBigInteger('template_id')->nullable()->index();

            $table->string('subject', 512)->nullable();
            $table->longText('body');

            // Status: queued | sent | delivered | failed | read
            $table->string('status', 30)->default('queued')->index();

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();

            // Raw provider response / error
            $table->json('provider_response')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'customer_id']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
