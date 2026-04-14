<?php

namespace App\Support\ModuleDoctor;

class ModuleReadinessMatrixService
{
    public function build(array $analysis): array
    {
        $checks = collect($analysis['checks'] ?? []);
        $visibility = $analysis['visibility_report'] ?? [];

        $flags = [
            'filesystem_ready' => $this->statusFromChecks($checks, ['Module folder structure', 'Module manifest', 'Provider namespace']),
            'provider_ready' => $this->statusFromChecks($checks, ['Module service provider', 'Route service provider', 'Parent product ID', 'Parent product name']),
            'routes_ready' => $this->statusFromChecks($checks, ['Route readiness', 'Route collision check', 'Migration filename conflicts']),
            'views_ready' => $this->statusFromChecks($checks, ['Sidebar readiness', 'Menu readiness']),
            'db_registered' => $this->statusFromChecks($checks, ['Existing module conflict']),
            'permissions_ready' => $this->statusFromChecks($checks, ['Permission readiness', 'Permission collision check']),
            'package_ready' => $this->statusFromChecks($checks, ['Package bridge readiness']),
            'module_settings_ready' => $this->statusFromChecks($checks, ['Company module settings', 'Tenant entitlement']),
            'menu_ready' => $this->statusFromChecks($checks, ['Menu readiness', 'Sidebar readiness']),
            'tenant_ready' => $this->tenantStatus($visibility['tenant'] ?? []),
            'cache_ready' => $this->cacheStatus($analysis),
        ];

        $statusScore = ['pass' => 1.0, 'warn' => 0.5, 'fail' => 0.0];
        $score = 0.0;
        foreach ($flags as $status) {
            $score += $statusScore[$status] ?? 0.0;
        }
        $score = (int) round(($score / max(count($flags), 1)) * 100);

        return [
            'flags' => $flags,
            'score' => $score,
            'status' => $score >= 90 ? 'pass' : ($score >= 65 ? 'warn' : 'fail'),
            'summary' => $this->buildSummary($flags, $score),
        ];
    }

    protected function statusFromChecks($checks, array $labels): string
    {
        $subset = $checks->filter(fn ($check) => in_array($check['label'] ?? '', $labels, true));
        if ($subset->isEmpty()) {
            return 'warn';
        }
        if ($subset->contains(fn ($check) => ($check['status'] ?? '') === 'fail')) {
            return 'fail';
        }
        if ($subset->contains(fn ($check) => ($check['status'] ?? '') === 'warn')) {
            return 'warn';
        }

        return 'pass';
    }

    protected function tenantStatus(array $tenant): string
    {
        if (empty($tenant)) {
            return 'warn';
        }
        foreach ($tenant as $profile) {
            if (($profile['status'] ?? '') === 'fail') {
                return 'fail';
            }
        }
        foreach ($tenant as $profile) {
            if (($profile['status'] ?? '') !== 'pass') {
                return 'warn';
            }
        }

        return 'pass';
    }

    protected function cacheStatus(array $analysis): string
    {
        if (!empty($analysis['auto_repairs']) && collect($analysis['auto_repairs'])->contains(fn ($repair) => str_contains(strtolower((string) ($repair['title'] ?? '')), 'cache'))) {
            return 'pass';
        }
        if (($analysis['installability_status'] ?? '') === 'ready') {
            return 'pass';
        }

        return 'warn';
    }

    protected function buildSummary(array $flags, int $score): string
    {
        $failed = array_keys(array_filter($flags, fn ($status) => $status === 'fail'));
        $warn = array_keys(array_filter($flags, fn ($status) => $status === 'warn'));

        if ($score >= 90) {
            return 'Readiness is strong across registry, routes, permissions, package, tenant, and menu layers.';
        }
        if (!empty($failed)) {
            return 'Blocking readiness gaps: ' . implode(', ', $failed) . '.';
        }

        return 'Warnings remain in: ' . implode(', ', $warn) . '.';
    }
}
