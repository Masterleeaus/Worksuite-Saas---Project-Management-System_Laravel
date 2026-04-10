<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_worker_location_pings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('person_id')->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('pinged_at')->useCurrent();
            $table->timestamps();

            $table->index(['person_id', 'pinged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_worker_location_pings');
    }
};
