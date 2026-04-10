<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_page_requests')) {
            return;
        }

        Schema::create('booking_page_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('booking_page_id')->nullable()->index();
            $table->string('page_slug')->nullable()->index();
            $table->string('service_name')->nullable();
            $table->string('customer_name');
            $table->string('email')->nullable()->index();
            $table->string('phone', 40)->nullable()->index();
            $table->string('postcode', 20)->nullable()->index();
            $table->date('preferred_date')->nullable()->index();
            $table->string('preferred_window', 100)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 40)->default('new')->index();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_page_requests');
    }
};
