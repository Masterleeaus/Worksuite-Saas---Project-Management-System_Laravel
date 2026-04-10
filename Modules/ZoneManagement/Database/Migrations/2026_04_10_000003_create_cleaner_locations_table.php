<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cleaner_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('booking_id')->nullable()->index();
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->float('accuracy')->nullable()
                ->comment('GPS accuracy in metres');
            $table->float('speed')->nullable()
                ->comment('Speed in m/s from device');
            $table->float('heading')->nullable()
                ->comment('Heading in degrees (0–360)');
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['user_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaner_locations');
    }
};
