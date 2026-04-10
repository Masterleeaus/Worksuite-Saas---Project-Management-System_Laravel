<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zone_pricing')) {
            Schema::create('zone_pricing', function (Blueprint $table) {
                $table->id();
                $table->uuid('zone_id')->index();
                $table->unsignedBigInteger('service_id')->nullable()->index()
                    ->comment('Foreign key to services table; null = applies to all services');
                $table->decimal('price_modifier', 5, 2)->default(0.00)
                    ->comment('Price modifier percentage, e.g. 10.00 = +10%, -5.00 = -5%');
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();

                $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_pricing');
    }
};
