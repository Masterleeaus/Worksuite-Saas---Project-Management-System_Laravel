<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_order_stock_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('fsm_stock_items')->onDelete('restrict');
            $table->decimal('qty_planned', 12, 4)->default(0);
            $table->decimal('qty_used', 12, 4)->nullable();
            $table->boolean('billable')->default(false);
            $table->string('state', 16)->default('planned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_order_stock_lines');
    }
};
