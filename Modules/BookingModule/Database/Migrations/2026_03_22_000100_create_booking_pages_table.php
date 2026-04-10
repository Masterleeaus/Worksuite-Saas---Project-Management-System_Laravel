<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_pages')) {
            return;
        }

        Schema::create('booking_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('status', 20)->default('draft')->index();
            $table->string('template', 100)->default('premium-home-cleaning');
            $table->string('headline');
            $table->text('subheadline')->nullable();
            $table->string('hero_badge', 100)->nullable();
            $table->string('primary_button_label', 100)->default('Book now');
            $table->string('primary_button_url')->default('/account/admin/booking/list');
            $table->string('secondary_button_label', 100)->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->longText('service_lines')->nullable();
            $table->longText('trust_lines')->nullable();
            $table->longText('faq_lines')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->json('theme')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_pages');
    }
};
