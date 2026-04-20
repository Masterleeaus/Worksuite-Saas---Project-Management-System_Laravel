<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('testimonials')) {
            return;
        }

        if (! Schema::hasColumn('testimonials', 'language_setting_id')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->unsignedInteger('language_setting_id')->nullable();
            });
        }

    }

    public function down(): void
    {
        if (! Schema::hasTable('testimonials') || ! Schema::hasColumn('testimonials', 'language_setting_id')) {
            return;
        }


        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('language_setting_id');
        });
    }
};
