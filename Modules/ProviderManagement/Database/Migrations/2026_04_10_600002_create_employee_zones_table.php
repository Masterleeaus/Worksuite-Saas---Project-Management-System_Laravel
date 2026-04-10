<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employee_zones')) {
            Schema::create('employee_zones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('zone_id');
                $table->timestamps();

                $table->unique(['employee_id', 'zone_id']);
                $table->index('employee_id');
                $table->index('zone_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_zones');
    }
};
