<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispatch_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('location_code', 64)->unique();
            $table->decimal('geo_longitude', 11, 8)->nullable();
            $table->decimal('geo_latitude', 10, 8)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_locations');
    }
};
