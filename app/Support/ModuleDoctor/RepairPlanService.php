<?php

namespace App\Support\ModuleDoctor;

class RepairPlanService
{
    public function __construct(
        protected ModuleReadinessMatrixService $readinessMatrixService,
        protected ModuleFixPlanBuilder $fixPlanBuilder,
    ) {
    }

    public function build(array $analysis): array
    {
        $readiness = $analysis['readiness_matrix'] ?? $this->readinessMatrixService->build($analysis);
        $plan = [
            [
                'title' => 'Protect custom UI and commands',
                'detail' => 'Installer package preserves resources/views/sections/menu.blade.php and existing app console commands by design.',
                'mode' => 'safe',
            ],
        ];

        if (($analysis['package_summary']['package_bridge_count'] ?? 0) == 0) {
            $plan[] = [
                'title' => 'Create package bridge records',
                'detail' => 'Attach the uploaded module to selected packages and seed company module_settings after install.',
                'mode' => 'safe',
            ];
        }

        if (!empty($analysis['package_summary']['inferred_parent_metadata'] ?? null)) {
            $plan[] = [
                'title' => 'Repair missing Worksuite metadata',
                'detail' => 'Fill missing parent_envato_id, parent_product_name, and parent_min_version values during install without touching your menu blade or app commands.',
                'mode' => 'safe',
            ];
        }

        if (($analysis['package_summary']['permission_signal_count'] ?? 0) == 0) {
            $plan[] = [
                'title' => 'Permission follow-up',
                'detail' => 'Run permission seeders when present, otherwise stop at a warning so roles can be mapped manually.',
                'mode' => 'safe',
            ];
        }

        if (($analysis['installability_status'] ?? '') === 'upgrade-candidate') {
            $plan[] = [
                'title' => 'Snapshot before replace',
                'detail' => 'Back up the installed module folder before copying the uploaded version into Modules/.',
                'mode' => 'safe',
            ];
        }

        if (in_array('warn', array_column($analysis['checks'] ?? [], 'status'), true)) {
            $plan[] = [
                'title' => 'Run doctor audit after install',
                'detail' => 'Confirm discovery, enablement, routes, migrations, package links, and module_settings after install.',
                'mode' => 'standard',
            ];
        }

        foreach ($this->fixPlanBuilder->build($analysis, $readiness) as $step) {
            $plan[] = [
                'title' => $step['title'],
                'detail' => 'Step ' . $step['order'] . ': ' . $step['detail'],
                'mode' => $step['risk'] ?? 'safe',
                'step_key' => $step['key'] ?? null,
                'reversible' => $step['reversible'] ?? true,
            ];
        }

        return $plan;
    }

    public function riskProfile(array $analysis): array
    {
        return [
            'workspace' => 'worksuite',
            'recommended_repair_level' => (($analysis['readiness_matrix']['status'] ?? null) === 'pass' || ($analysis['installability_status'] ?? '') === 'ready') ? 'safe' : 'standard',
            'auto_fixable_count' => count($this->build($analysis)),
            'readiness_score' => (int) ($analysis['readiness_matrix']['score'] ?? 0),
            'protected_paths' => [
                'resources/views/sections/menu.blade.php',
                'app/Console/Commands/*',
            ],
        ];
    }
}
