<?php

use App\Models\GlobalSetting;
use App\Models\Module;
use App\Models\SuperAdmin\Package;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (Schema::hasTable('packages') && !Schema::hasColumn('packages', 'package')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->string('package')->nullable()->after('annual_status');
            });
        }

        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'default') && in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `packages`
                MODIFY COLUMN `default`
                ENUM('yes', 'no', 'trial', 'lifetime') NOT NULL DEFAULT 'no'");
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
};
