<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('titan_theme_presets')) {
            Schema::create('titan_theme_presets', function (Blueprint $table) {
                $table->id();

                $table->unsignedInteger('company_id')->nullable()->index();
                $table->foreign('company_id')
                    ->references('id')->on('companies')
                    ->onDelete('cascade')->onUpdate('cascade');

                $table->string('name');
                $table->text('description')->nullable();

                // Colours
                $table->string('primary_color', 20)->nullable();
                $table->string('secondary_color', 20)->nullable();
                $table->string('accent_color', 20)->nullable();
                $table->string('background_color', 20)->nullable();
                $table->string('text_color', 20)->nullable();

                // Typography
                $table->string('heading_font', 100)->nullable();
                $table->string('body_font', 100)->nullable();

                // Layout
                $table->unsignedSmallInteger('sidebar_width')->nullable();
                $table->unsignedSmallInteger('header_height')->nullable();
                $table->unsignedTinyInteger('border_radius')->nullable();

                // Custom CSS override
                $table->longText('custom_css')->nullable();

                // Additional JSON settings
                $table->json('extra_settings')->nullable();

                $table->boolean('is_active')->default(false);

                $table->unsignedInteger('created_by')->nullable();
                $table->foreign('created_by')
                    ->references('id')->on('users')
                    ->onDelete('set null')->onUpdate('cascade');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_theme_presets');
    }
};
