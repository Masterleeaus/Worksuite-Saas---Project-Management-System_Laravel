<?php

use App\Models\Company;
use App\Scopes\ActiveScope;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Subdomain\Entities\SubdomainSetting;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('companies', 'sub_domain')) {
            if (Schema::hasTable('companies')) {
        Schema::table('companies', function (Blueprint $table) {
                if (!Schema::hasColumn('companies', 'sub_domain')) {
                    $table->string('sub_domain')->after('id')->nullable();
                }
        });
    }

            $companies = Company::withoutGlobalScope(ActiveScope::class)
                ->select(['id', 'app_name'])
                ->whereNull('sub_domain')
                ->get();

            foreach ($companies as $company) {
                SubdomainSetting::addDefaultSubdomain($company);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('companies')) {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'sub_domain')) {
                $table->dropColumn('sub_domain');
            }
        });
    }
    }

};
