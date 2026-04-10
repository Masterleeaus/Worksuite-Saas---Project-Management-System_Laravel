<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_stock_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->foreign('category_id')->references('id')->on('fsm_stock_categories')->onDelete('set null');
            $table->string('name', 128);
            $table->text('description')->nullable();
            $table->string('unit', 32)->default('units');
            $table->decimal('current_qty', 12, 4)->default(0);
            $table->decimal('min_qty', 12, 4)->default(0);
            $table->decimal('cost_price', 12, 4)->default(0);
            $table->string('supplier', 191)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_stock_items');
    }
};
