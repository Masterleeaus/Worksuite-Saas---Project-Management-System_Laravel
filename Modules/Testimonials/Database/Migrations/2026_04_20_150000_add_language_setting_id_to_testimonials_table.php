<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('testimonials') || Schema::hasColumn('testimonials', 'language_setting_id')) {
            return;
        }

        Schema::table('testimonials', function (Blueprint $table) {
            $table->unsignedInteger('language_setting_id')->nullable();

            if (Schema::hasTable('language_settings')) {
                $table->foreign('language_setting_id')
                    ->references('id')
                    ->on('language_settings')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('testimonials') || !Schema::hasColumn('testimonials', 'language_setting_id')) {
            return;
        }

        Schema::table('testimonials', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                try {
                    $table->dropForeign(['language_setting_id']);
                } catch (\Throwable $th) {
                    // no-op
                }
            }

            $table->dropColumn('language_setting_id');
        });
    }
};
