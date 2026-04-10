<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('booking_id')->nullable()->index();
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->float('accuracy')->nullable();
            $table->unsignedInteger('sequence')->default(0)
                ->comment('Point order within the route');
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['booking_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_points');
    }
};
