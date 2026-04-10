<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fsm_portal_reclean_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('requested_by')->index(); // client user_id
            $table->text('reason')->nullable();
            $table->string('status', 32)->default('pending'); // pending, accepted, rejected
            $table->unsignedBigInteger('fsm_activity_id')->nullable(); // follow-up FSMActivity if created
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_portal_reclean_requests');
    }
};
