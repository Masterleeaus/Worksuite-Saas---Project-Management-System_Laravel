<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payroll_run_overrides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('line_item_id');
            $table->foreign('line_item_id')->references('id')->on('payroll_run_line_items')->onDelete('cascade');
            $table->integer('overridden_by')->unsigned();
            $table->foreign('overridden_by')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('original_total_pay', 10, 4);
            $table->decimal('new_total_pay', 10, 4);
            $table->text('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_run_overrides');
    }
};
