<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_stock_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('fsm_order_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->string('status', 32)->default('draft');
            $table->text('notes')->nullable();
            $table->date('requested_date')->nullable();
            $table->timestamps();

            $table->foreign('fsm_order_id')->references('id')->on('fsm_orders')->onDelete('set null');
            $table->foreign('invoice_id')->references('id')->on('fsm_sales_invoices')->onDelete('set null');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_stock_requisitions');
    }
};
