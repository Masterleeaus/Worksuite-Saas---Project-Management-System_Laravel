<?php

namespace Modules\BusinessSettingsModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BusinessSettingsModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (!Schema::hasTable('modules')) {
            return;
        }

        $moduleName = 'businesssettingsmodule';

        $columns = Schema::getColumnListing('modules');
        $payload = [];

        if (in_array('description', $columns, true)) {
            $payload['description'] = 'Business settings and subscriptions';
        }

        if (in_array('created_at', $columns, true)) {
            $payload['created_at'] = now();
        }

        if (in_array('updated_at', $columns, true)) {
            $payload['updated_at'] = now();
        }

        DB::table('modules')->updateOrInsert(['module_name' => $moduleName], $payload);
    }
}
