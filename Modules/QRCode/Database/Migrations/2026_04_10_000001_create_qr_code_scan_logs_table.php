<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('qr_code_scan_logs')) {
            Schema::create('qr_code_scan_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('qr_code_data_id')->index();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('scanned_at')->nullable();
                $table->timestamps();

                $table->foreign('qr_code_data_id')
                    ->references('id')
                    ->on('qr_code_data')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_code_scan_logs');
    }
};
