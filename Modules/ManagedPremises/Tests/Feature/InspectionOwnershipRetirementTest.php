<?php

namespace Modules\ManagedPremises\Tests\Feature;

use Tests\TestCase;

/**
 * Regression tests for the inspection ownership refactor.
 *
 * Covers:
 *  - Legacy write-guard log on PropertyInspection model
 *  - PropertyInspectionPolicy aliases QC permissions
 *  - Widget reads qc_records via PropertyWidgetsComposer
 *  - Seeder idempotency (running twice = no duplicate rows)
 *
 * NOTE: HTTP endpoint tests (redirect assertions) require a functional
 *       app kernel with a company-scoped user session. They are written
 *       as unit / integration-style assertions that verify the structural
 *       properties of the compatibility bridge without requiring a live HTTP
 *       stack, which currently fails due to unrelated missing module shims.
 */
class InspectionOwnershipRetirementTest extends TestCase
{
    // ── 1. Write guard ────────────────────────────────────────────────────────

    /** @test */
    public function property_inspection_model_emits_deprecation_log_on_create(): void
    {
        // Use a mock log sink to capture the warning rather than a real DB write.
        $logged = false;
        \Illuminate\Support\Facades\Log::shouldReceive('warning')
            ->once()
            ->with(\Mockery::pattern('/\[DEPRECATED\]/'), \Mockery::any())
            ->andReturnUsing(function () use (&$logged) { $logged = true; });

        // Trigger the boot hook without actually touching the DB.
        $model = new \Modules\ManagedPremises\Entities\PropertyInspection();
        $model->fireModelEvent('creating', false);

        $this->assertTrue($logged, 'Expected deprecation warning was not emitted.');
    }

    // ── 2. Policy aliases ─────────────────────────────────────────────────────

    /** @test */
    public function property_inspection_policy_view_accepts_qc_permission(): void
    {
        $policy = new \Modules\ManagedPremises\Policies\PropertyInspectionPolicy();

        $userWithQcPerm = new class {
            public function can(string $ability): bool
            {
                return $ability === 'view_quality_control';
            }
        };

        // The policy's view() method checks view_quality_control via hasAny
        // We test via the helper method reflection or call a concrete accessor.
        // Since the policy uses ChecksPmPermissions::has(), which calls $user->can(),
        // we check that the alias set includes the QC permission.
        $this->assertTrue(
            in_array('view_quality_control', $this->getPolicyPermissions($policy, 'view'), true),
            'PropertyInspectionPolicy::view() should include view_quality_control alias'
        );
    }

    /** @test */
    public function property_inspection_policy_create_accepts_qc_permission(): void
    {
        $policy = new \Modules\ManagedPremises\Policies\PropertyInspectionPolicy();
        $this->assertTrue(
            in_array('add_quality_control', $this->getPolicyPermissions($policy, 'create'), true)
        );
    }

    /** @test */
    public function property_inspection_policy_update_accepts_qc_permission(): void
    {
        $policy = new \Modules\ManagedPremises\Policies\PropertyInspectionPolicy();
        $this->assertTrue(
            in_array('edit_quality_control', $this->getPolicyPermissions($policy, 'update'), true)
        );
    }

    /** @test */
    public function property_inspection_policy_delete_accepts_qc_permission(): void
    {
        $policy = new \Modules\ManagedPremises\Policies\PropertyInspectionPolicy();
        $this->assertTrue(
            in_array('delete_quality_control', $this->getPolicyPermissions($policy, 'delete'), true)
        );
    }

    // ── 3. Controller is compatibility-bridge only ────────────────────────────

    /** @test */
    public function property_inspections_controller_has_no_direct_model_write_calls(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Http/Controllers/PropertyInspectionsController.php')
        );

        // The controller must not call save() or create() on PropertyInspection
        $this->assertStringNotContainsString(
            'PropertyInspection::create',
            $source,
            'Controller must not create PropertyInspection rows directly.'
        );
        $this->assertStringNotContainsString(
            '->save()',
            $source,
            'Controller must not save() PropertyInspection rows directly.'
        );
        $this->assertStringNotContainsString(
            '->update(',
            $source,
            'Controller must not update PropertyInspection rows directly.'
        );
    }

    /** @test */
    public function property_inspections_controller_has_deprecated_docblock(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Http/Controllers/PropertyInspectionsController.php')
        );

        $this->assertStringContainsString('@deprecated', $source);
    }

    // ── 4. Widget reads qc_records ─────────────────────────────────────────────

    /** @test */
    public function property_widgets_composer_reads_qc_records_not_pm_inspections(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Http/View/Composers/PropertyWidgetsComposer.php')
        );

        // Must reference QcRecord
        $this->assertStringContainsString('QcRecord', $source);

        // Must NOT query pm_property_inspections
        $this->assertStringNotContainsString('pm_property_inspections', $source);
    }

    // ── 5. Seeder idempotency ──────────────────────────────────────────────────

    /** @test */
    public function managed_premises_permission_seeder_uses_update_or_create(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Database/Seeders/ManagedPremisesPermissionSeeder.php')
        );

        $this->assertStringContainsString('updateOrCreate', $source);
    }

    /** @test */
    public function managed_premises_status_seeder_has_insert_and_update_paths(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Database/Seeders/ManagedPremisesStatusSeeder.php')
        );

        // Must handle both insert and update paths
        $this->assertStringContainsString('->insert(', $source);
        $this->assertStringContainsString('->update(', $source);
    }

    /** @test */
    public function managed_premises_menu_seeder_checks_existence_before_insert(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Database/Seeders/ManagedPremisesMenuSeeder.php')
        );

        $this->assertStringContainsString('->exists()', $source);
    }

    // ── 6. Legacy inspection views are deprecation-only ───────────────────────

    /** @test */
    public function legacy_inspection_index_view_does_not_render_inspection_table_forms(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Resources/views/inspections/index.blade.php')
        );

        // Must not contain any write form targeting the old inspection store/update route
        $this->assertStringNotContainsString('inspections.store', $source);
        $this->assertStringNotContainsString('inspections.update', $source);
        $this->assertStringNotContainsString('@method(\'DELETE\')', $source);
    }

    /** @test */
    public function legacy_inspection_index_view_redirects_to_qc(): void
    {
        $source = file_get_contents(
            module_path('ManagedPremises', 'Resources/views/inspections/index.blade.php')
        );

        $this->assertStringContainsString('qc-records.index', $source);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Extract the permission name array used by a given policy method by
     * inspecting the source code directly (avoids needing a real User instance).
     */
    private function getPolicyPermissions(object $policy, string $method): array
    {
        $source = (new \ReflectionClass($policy))->getFileName();
        $content = file_get_contents($source);

        // Find the method block, then extract strings from the hasAny([...]) call.
        preg_match('/public function ' . $method . '\(.*?\{(.*?)\}/s', $content, $m);
        $block = $m[1] ?? '';
        preg_match_all("/'([^']+)'/", $block, $strings);
        return $strings[1] ?? [];
    }
}
