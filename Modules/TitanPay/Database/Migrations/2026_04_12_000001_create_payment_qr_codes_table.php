<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_qr_codes')) {
            Schema::create('payment_qr_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('invoice_id')->index();
                $table->text('payment_url');
                $table->string('qr_image_path')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('scanned_at')->nullable();
                $table->unsignedSmallInteger('scan_count')->default(0);
                $table->enum('status', ['active', 'used', 'expired'])->default('active');
                $table->timestamps();

                $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_qr_codes');
    }
};
