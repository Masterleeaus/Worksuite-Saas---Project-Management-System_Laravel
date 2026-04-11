<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tenan_parkir')) {
            return;
        }

        Schema::create('tenan_parkir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->string('vehicle_plate');
            $table->string('vehicle_type')->nullable();
            $table->string('bay_number')->nullable();
            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->string('status')->default('parked');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenan_parkir');
    }
};
