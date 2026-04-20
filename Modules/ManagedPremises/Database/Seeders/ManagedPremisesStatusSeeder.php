<?php

namespace Modules\ManagedPremises\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManagedPremisesStatusSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('pm_settings') || !Schema::hasTable('companies')) {
            return;
        }

        $defaults = [
            'premise_statuses' => ['active', 'inactive', 'archived'],
            'approval_statuses' => ['pending', 'approved', 'rejected'],
            'hazard_severities' => ['low', 'medium', 'high', 'critical'],
            'service_window_types' => ['business_hours', 'after_hours', 'weekend'],
            'service_plan_states' => ['draft', 'active', 'paused', 'archived'],
            'premise_tag_types' => ['residential', 'commercial', 'industrial'],
        ];

        $companies = DB::table('companies')->select('id')->get();

        foreach ($companies as $company) {
            $row = DB::table('pm_settings')->where('company_id', $company->id)->first();
            $current = [];
            if ($row?->settings_json) {
                $decoded = json_decode((string) $row->settings_json, true);
                if (is_array($decoded)) {
                    $current = $decoded;
                }
            }

            $merged = array_replace_recursive($defaults, $current);

            if ($row) {
                DB::table('pm_settings')->where('id', $row->id)->update([
                    'settings_json' => json_encode($merged),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
