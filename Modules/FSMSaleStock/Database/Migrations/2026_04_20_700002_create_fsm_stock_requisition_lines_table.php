<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_stock_requisition_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->string('product_name', 256);
            $table->string('part_number', 128)->nullable();
            $table->decimal('quantity', 10, 3)->default(1.000);
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('requisition_id')->references('id')->on('fsm_stock_requisitions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_stock_requisition_lines');
    }
};
