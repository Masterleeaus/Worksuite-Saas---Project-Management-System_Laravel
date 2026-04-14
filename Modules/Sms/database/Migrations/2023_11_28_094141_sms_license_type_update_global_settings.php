<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sms_settings') || Schema::hasColumn('sms_settings', 'license_type')) {
            return;
        }

        $afterColumn = Schema::hasColumn('sms_settings', 'supported_until') ? 'supported_until' : null;

        Schema::table('sms_settings', function (Blueprint $table) use ($afterColumn) {
            $column = $table->string('license_type', 20)->nullable();
            if ($afterColumn) {
                $column->after($afterColumn);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sms_settings') || ! Schema::hasColumn('sms_settings', 'license_type')) {
            return;
        }

        Schema::table('sms_settings', function (Blueprint $table) {
            $table->dropColumn('license_type');
        });
    }
};
