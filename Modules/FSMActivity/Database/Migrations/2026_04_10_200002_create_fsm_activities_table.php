<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('activity_type_id')->nullable()->index();
            $table->string('summary', 255)->nullable();
            $table->text('note')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable()->index();
            $table->string('state', 16)->default('open'); // open, done, cancelled, overdue
            $table->dateTime('done_at')->nullable();
            $table->unsignedBigInteger('done_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->cascadeOnDelete();
            $table->foreign('activity_type_id')->references('id')->on('fsm_activity_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_activities');
    }
};
