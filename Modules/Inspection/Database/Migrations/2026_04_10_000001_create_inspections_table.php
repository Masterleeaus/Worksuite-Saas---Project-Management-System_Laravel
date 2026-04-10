<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('inspections')) {
            return;
        }

        Schema::create('inspections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')
                ->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('booking_id')->nullable()->index();
            $table->unsignedInteger('inspector_id')->nullable();
            $table->foreign('inspector_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('template_id')->nullable()->index();
            $table->decimal('score', 5, 2)->nullable()->comment('0-10 quality score');
            $table->enum('status', [
                'pending',
                'in_progress',
                'passed',
                'failed',
                'reclean_booked',
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('inspected_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('reclean_booking_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
