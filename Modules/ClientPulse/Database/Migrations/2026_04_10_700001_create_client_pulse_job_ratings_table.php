<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_pulse_job_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('client_id')->index();  // the client who rated
            $table->unsignedBigInteger('cleaner_id')->nullable()->index(); // the assigned cleaner
            $table->unsignedTinyInteger('stars')->comment('1-5 star rating');
            $table->text('comment')->nullable();
            $table->timestamp('rated_at')->nullable();
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
            $table->unique(['fsm_order_id', 'client_id']); // one rating per order per client
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_pulse_job_ratings');
    }
};
