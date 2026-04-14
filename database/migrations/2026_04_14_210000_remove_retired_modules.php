<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $studlyModules = [
            'Affiliate',
            'BidModule',
            'Biolinks',
            'Blogs',
            'CampaignCanvas',
            'CategoryManagement',
            'ChattingModule',
            'Communication',
            'CustomerConnect',
            'CyberSecurity',
            'Documents',
            'Faq',
            'GlobalSetting',
            'Inventory',
            'Letter',
            'MenuBuilder',
            'Newsletter',
            'PaymentModule',
            'Payroll',
            'Performance',
            'ProShots',
            'Product',
            'ProjectRoadmap',
            'QRCode',
            'Recruit',
            'SMSModule',
            'Security',
            'Service',
            'Subdomain',
            'Suppliers',
            'Support',
            'TitanAgents',
            'TitanHello',
            'TitanPWA',
            'TitanPay',
            'TitanReach',
            'TitanTalk',
            'TitanZero',
            'TransactionModule',
            'Webhooks',
            'Workflow',
            'Zoom',
        ];

        $moduleNameCandidates = collect($studlyModules)
            ->flatMap(fn (string $name) => [$name, strtolower($name)])
            ->unique()
            ->values()
            ->all();

        if (Schema::hasTable('module_registry_items')) {
            DB::table('module_registry_items')
                ->whereIn('module_name', $moduleNameCandidates)
                ->orWhereIn('slug', collect($studlyModules)->map(fn (string $name) => strtolower($name))->all())
                ->delete();
        }

        if (Schema::hasTable('module_registry_feeds')) {
            DB::table('module_registry_feeds')
                ->whereIn('name', $moduleNameCandidates)
                ->orWhereIn('slug', collect($studlyModules)->map(fn (string $name) => strtolower($name))->all())
                ->delete();
        }

        if (Schema::hasTable('company_module_settings')) {
            DB::table('company_module_settings')->whereIn('module_name', $moduleNameCandidates)->delete();
        }

        if (Schema::hasTable('module_settings')) {
            DB::table('module_settings')->whereIn('module_name', $moduleNameCandidates)->delete();
        }

        if (Schema::hasTable('modules')) {
            DB::table('modules')->whereIn('module_name', $moduleNameCandidates)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank. Removed modules are not restored automatically.
    }
};
