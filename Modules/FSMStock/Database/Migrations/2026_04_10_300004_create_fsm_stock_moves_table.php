<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_stock_moves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_id')->nullable()->index();
            $table->unsignedBigInteger('fsm_order_stock_line_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->decimal('qty', 12, 4);
            $table->string('direction', 8)->default('out');
            $table->string('reason', 191)->nullable();
            $table->unsignedBigInteger('moved_by')->nullable();
            $table->timestamp('moved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_stock_moves');
    }
};
