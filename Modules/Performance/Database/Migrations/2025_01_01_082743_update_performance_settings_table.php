<?php

use App\Models\Company;
use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Performance\Entities\PerformanceSetting;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('performance_settings', 'company_id')) {
            if (! Schema::hasTable('performance_settings')) {
                return;
            }
            
            Schema::table('performance_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('performance_settings', 'company_id')) {
                    $table->integer('company_id')->unsigned()->nullable()->after('id');
                }
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (Schema::hasColumn('performance_settings', 'purchase_code')) {
            if (! Schema::hasTable('performance_settings')) {
                return;
            }
            
            Schema::table('performance_settings', function (Blueprint $table) {
                $table->dropColumn('purchase_code');
                $table->dropColumn('supported_until');
                $table->dropColumn('notify_update');
            });
        }

        $companies = Company::select('id', 'package_id')->get();

        foreach ($companies as $company) {
            PerformanceSetting::firstOrCreate(['company_id' => $company->id]);
            PerformanceSetting::addModuleSetting($company);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_settings');
    }
};
