<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_links')) {
            Schema::create('payment_links', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('invoice_id')->index();
                $table->string('token', 64)->unique();
                $table->string('gateway')->default('any');
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('used_at')->nullable();
                $table->enum('status', ['active', 'used', 'expired'])->default('active');
                $table->timestamps();

                $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_links');
    }
};
