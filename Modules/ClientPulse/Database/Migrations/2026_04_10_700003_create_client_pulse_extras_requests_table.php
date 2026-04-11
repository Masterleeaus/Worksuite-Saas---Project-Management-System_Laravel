<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_pulse_extras_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('client_id')->index();
            $table->unsignedBigInteger('fsm_order_id')->nullable()->index(); // next scheduled job
            $table->json('items')->nullable();            // array of ExtrasItem ids selected
            $table->text('custom_note')->nullable();      // free-text extra note
            $table->string('status', 32)->default('pending'); // pending, acknowledged, added_to_job
            $table->timestamp('acknowledged_at')->nullable();
            $table->unsignedBigInteger('acknowledged_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_pulse_extras_requests');
    }
};
