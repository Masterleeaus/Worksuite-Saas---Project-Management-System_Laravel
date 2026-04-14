<?php

namespace App\Support\ModuleDoctor;

class ModuleFixPlanBuilder
{
    public function build(array $analysis, array $readiness): array
    {
        $flags = $readiness['flags'] ?? [];
        $steps = [];
        $push = function (string $key, string $title, string $detail, string $risk = 'safe', bool $reversible = true) use (&$steps) {
            $steps[] = compact('key', 'title', 'detail', 'risk', 'reversible');
        };

        if (($flags['db_registered'] ?? 'warn') !== 'pass') {
            $push('register_module', 'Register module row', 'Create or reconcile the modules-table entry so registry, package UI, and module doctor all reference one canonical slug.', 'safe');
        }
        if (($flags['permissions_ready'] ?? 'warn') !== 'pass') {
            $push('seed_permissions', 'Seed permissions', 'Create missing permission keys and patch role mappings so routes and sidebar gates stop failing.', 'safe');
        }
        if (($flags['module_settings_ready'] ?? 'warn') !== 'pass') {
            $push('seed_module_settings', 'Seed company module settings', 'Insert missing module_settings rows for existing companies and default role profiles.', 'safe');
        }
        if (($flags['package_ready'] ?? 'warn') !== 'pass') {
            $push('sync_package_bridge', 'Sync package bridge', 'Attach the module to eligible packages and align package entitlements with company visibility.', 'safe');
        }
        if (($flags['menu_ready'] ?? 'warn') !== 'pass' || ($flags['views_ready'] ?? 'warn') !== 'pass') {
            $push('repair_menu_surface', 'Repair menu visibility', 'Resolve menu/sidebar registration so the module appears on the intended user and admin surfaces.', 'standard');
        }
        if (($flags['routes_ready'] ?? 'warn') !== 'pass' || ($flags['provider_ready'] ?? 'warn') !== 'pass') {
            $push('repair_boot_chain', 'Repair boot chain', 'Correct provider or route loading mismatches before attempting install or upgrade.', 'standard', false);
        }
        if (($flags['tenant_ready'] ?? 'warn') !== 'pass') {
            $push('verify_tenant_scope', 'Verify tenant visibility', 'Review company-bound entitlement and runtime access rules to avoid installed-but-invisible modules.', 'standard', false);
        }

        $push('clear_caches', 'Clear caches', 'Refresh route, config, menu, and view caches after repairs so visibility changes take effect.', 'safe');

        foreach ($steps as $index => &$step) {
            $step['order'] = $index + 1;
        }

        return $steps;
    }
}
