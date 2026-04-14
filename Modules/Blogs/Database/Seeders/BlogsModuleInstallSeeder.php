<?php

namespace Modules\Blogs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BlogsModuleInstallSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('modules')) {
            return;
        }

        $moduleColumns = Schema::getColumnListing('modules');
        $payload = ['module_name' => 'blogs'];

        if (in_array('description', $moduleColumns, true)) {
            $payload['description'] = 'Blogs module';
        }

        if (in_array('is_superadmin', $moduleColumns, true)) {
            $payload['is_superadmin'] = 0;
        }

        $module = DB::table('modules')->where('module_name', 'blogs')->first();
        if ($module) {
            DB::table('modules')->where('id', $module->id)->update($payload);
        } else {
            $payload['created_at'] = now();
            $payload['updated_at'] = now();
            DB::table('modules')->insert($payload);
        }

        if (!Schema::hasTable('module_settings')) {
            return;
        }

        $columns = Schema::getColumnListing('module_settings');
        $types = ['admin', 'employee', 'client'];
        $companyIds = [null];

        if (Schema::hasTable('companies') && in_array('company_id', $columns, true)) {
            $companyIds = DB::table('companies')->pluck('id')->all();
            if (empty($companyIds)) {
                $companyIds = [null];
            }
        }

        foreach ($companyIds as $companyId) {
            foreach ($types as $type) {
                $query = DB::table('module_settings')
                    ->where('module_name', 'blogs')
                    ->where('type', $type);

                if (in_array('company_id', $columns, true)) {
                    $companyId === null ? $query->whereNull('company_id') : $query->where('company_id', $companyId);
                }

                if (!$query->exists()) {
                    $insert = [
                        'module_name' => 'blogs',
                        'status' => 'active',
                        'type' => $type,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (in_array('company_id', $columns, true)) {
                        $insert['company_id'] = $companyId;
                    }

                    if (in_array('is_allowed', $columns, true)) {
                        $insert['is_allowed'] = 1;
                    }

                    DB::table('module_settings')->insert($insert);
                }
            }
        }
    }
}
