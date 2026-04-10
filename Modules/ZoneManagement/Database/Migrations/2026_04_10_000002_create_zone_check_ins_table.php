<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zone_check_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('booking_id')->nullable()->index()
                ->comment('FK to bookings.id (UUID string)');
            $table->unsignedBigInteger('user_id')->index()
                ->comment('The cleaner / serviceman');
            $table->decimal('check_in_lat', 10, 8)->nullable();
            $table->decimal('check_in_lng', 11, 8)->nullable();
            $table->float('check_in_accuracy')->nullable()
                ->comment('GPS accuracy in metres at check-in');
            $table->timestamp('checked_in_at')->nullable();
            $table->decimal('check_out_lat', 10, 8)->nullable();
            $table->decimal('check_out_lng', 11, 8)->nullable();
            $table->float('check_out_accuracy')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->boolean('is_verified')->default(true)
                ->comment('false when GPS accuracy > 50 m or unavailable');
            $table->boolean('within_geofence')->default(true)
                ->comment('Whether cleaner was inside zone radius on check-in');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_check_ins');
    }
};
