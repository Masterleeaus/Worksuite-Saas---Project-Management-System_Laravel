<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('security_access_logs')) {
            return;
        }
        Schema::create('security_access_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('access_card_id')->nullable();
            $table->unsignedBigInteger('inout_permit_id')->nullable();
            $table->unsignedBigInteger('work_permit_id')->nullable();
            $table->unsignedBigInteger('parking_id')->nullable();

            // Event tracking
            $table->string('event_type'); // badge_swipe, entry_granted, entry_denied, exit, vehicle_entry, permit_presented, alert
            $table->string('status'); // granted, denied, pending, alert
            $table->string('location')->nullable(); // gate_a, gate_b, main_entrance, vehicle_gate, etc.

            // Technical info
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('reason_denied')->nullable();
            $table->integer('duration_seconds')->nullable();

            // Timestamp
            $table->dateTime('timestamp');
            $table->timestamps();

            // Indexes for fast querying
            $table->index(['company_id', 'timestamp']);
            $table->index(['unit_id', 'timestamp']);
            $table->index(['access_card_id', 'timestamp']);
            $table->index(['inout_permit_id', 'timestamp']);
            $table->index(['work_permit_id', 'timestamp']);
            $table->index(['parking_id', 'timestamp']);
            $table->index(['event_type', 'timestamp']);
            $table->index(['status', 'timestamp']);
            $table->index(['location', 'timestamp']);

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_access_logs');
    }
};
