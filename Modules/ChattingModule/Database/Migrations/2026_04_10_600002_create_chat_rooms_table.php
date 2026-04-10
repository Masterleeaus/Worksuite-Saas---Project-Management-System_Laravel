<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the chat_rooms table for group/booking/broadcast channels.
     * Does NOT duplicate the core users_chat table.
     */
    public function up(): void
    {
        if (!Schema::hasTable('chat_rooms')) {
            Schema::create('chat_rooms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->string('type')->default('group');
                // Allowed values: 'group','booking','broadcast'
                $table->char('booking_id', 36)->nullable()->index();
                // booking_id references bookings.id (UUID); nullable — not a strict FK
                // because BookingModule may not always be installed
                $table->json('member_ids');
                $table->unsignedBigInteger('created_by');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
