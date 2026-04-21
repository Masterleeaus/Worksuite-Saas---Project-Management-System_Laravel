<?php

namespace Modules\ManagedPremises\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManagedPremisesSettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('pm_settings') || !Schema::hasTable('companies')) {
            return;
        }

        $defaults = (array) config('managedpremises.manifests.settings', []);

        foreach (DB::table('companies')->select('id')->get() as $company) {
            $existing = DB::table('pm_settings')->where('company_id', $company->id)->first();
            $userId = DB::table('users')->where('company_id', $company->id)->value('id') ?: DB::table('users')->value('id');

            if (!$existing) {
                DB::table('pm_settings')->insert([
                    'company_id' => $company->id,
                    'user_id' => $userId,
                    'settings_json' => json_encode($defaults),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                continue;
            }

            $current = $existing->settings_json ? (json_decode((string) $existing->settings_json, true) ?: []) : [];
            $merged = array_replace_recursive($defaults, $current);

            DB::table('pm_settings')->where('id', $existing->id)->update([
                'settings_json' => json_encode($merged),
                'updated_at' => now(),
            ]);
        }
    }
}
