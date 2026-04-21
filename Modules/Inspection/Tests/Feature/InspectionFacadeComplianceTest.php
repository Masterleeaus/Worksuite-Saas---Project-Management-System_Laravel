<?php

namespace Modules\Inspection\Tests\Feature;

use Tests\TestCase;

/**
 * Compliance test suite: verifies that the Inspection module is a fully compliant
 * compatibility façade over the QualityControl scheduling engine.
 *
 * Coverage areas:
 *   1. All 8 Inspection controllers are thin delegates over QC controllers
 *   2. Support class delegation (enums, permissions, routes, template packs)
 *   3. Enum constant parity between Inspection and QC
 *   4. Permission delegation helpers work correctly
 *   5. Template pack delegation resolves via QC registry
 *   6. Migration retirement stub exists and is a no-op
 *   7. Manifest consistency across all three manifest files
 */
class InspectionFacadeComplianceTest extends TestCase
{
    // =========================================================================
    // 1. CONTROLLER DELEGATION – all 8 controllers must extend QC counterparts
    // =========================================================================

    /** @test */
    public function schedule_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\ScheduleController::class,
                \Modules\QualityControl\Http\Controllers\ScheduleController::class
            ),
            'Inspection\ScheduleController must extend QC\ScheduleController.'
        );
    }

    /** @test */
    public function schedule_inspection_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\ScheduleInspectionController::class,
                \Modules\QualityControl\Http\Controllers\ScheduleInspectionController::class
            ),
            'Inspection\ScheduleInspectionController must extend QC\ScheduleInspectionController.'
        );
    }

    /** @test */
    public function inspection_template_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\InspectionTemplateController::class,
                \Modules\QualityControl\Http\Controllers\InspectionTemplateController::class
            ),
            'Inspection\InspectionTemplateController must extend QC\InspectionTemplateController.'
        );
    }

    /** @test */
    public function recurring_schedule_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\RecurringScheduleController::class,
                \Modules\QualityControl\Http\Controllers\RecurringScheduleController::class
            ),
            'Inspection\RecurringScheduleController must extend QC\RecurringScheduleController.'
        );
    }

    /** @test */
    public function schedule_file_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\ScheduleFileController::class,
                \Modules\QualityControl\Http\Controllers\ScheduleFileController::class
            ),
            'Inspection\ScheduleFileController must extend QC\ScheduleFileController.'
        );
    }

    /** @test */
    public function schedule_reply_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\ScheduleReplyController::class,
                \Modules\QualityControl\Http\Controllers\ScheduleReplyController::class
            ),
            'Inspection\ScheduleReplyController must extend QC\ScheduleReplyController.'
        );
    }

    /** @test */
    public function inspection_template_item_controller_is_bridge_over_qc(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\InspectionTemplateItemController::class,
                \Modules\QualityControl\Http\Controllers\InspectionTemplateItemController::class
            ),
            'Inspection\InspectionTemplateItemController must extend QC\InspectionTemplateItemController.'
        );
    }

    /** @test */
    public function inspection_controller_delegates_to_qc_execution_record_service(): void
    {
        // InspectionController does not extend a QC controller (its API shape differs),
        // but it must inject QC's ExecutionRecordService rather than any Inspection-owned service.
        $reflect = new \ReflectionClass(\Modules\Inspection\Http\Controllers\InspectionController::class);
        $constructor = $reflect->getConstructor();

        $found = false;
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();
            if ($type && $type->getName() === \Modules\QualityControl\Services\ExecutionRecordService::class) {
                $found = true;
                break;
            }
        }

        $this->assertTrue(
            $found,
            'InspectionController must inject QC\Services\ExecutionRecordService, not any Inspection-owned service.'
        );
    }

    // =========================================================================
    // 2. SUPPORT CLASS DELEGATION
    // =========================================================================

    /** @test */
    public function inspection_status_enum_constants_forward_to_qc_values(): void
    {
        $inp = \Modules\Inspection\Support\Enums\InspectionStatus::class;
        $qc  = \Modules\QualityControl\Support\Enums\InspectionStatus::class;

        $this->assertSame($qc::PENDING,        $inp::PENDING);
        $this->assertSame($qc::IN_PROGRESS,    $inp::IN_PROGRESS);
        $this->assertSame($qc::PASSED,         $inp::PASSED);
        $this->assertSame($qc::FAILED,         $inp::FAILED);
        $this->assertSame($qc::RECLEAN_BOOKED, $inp::RECLEAN_BOOKED);
    }

    /** @test */
    public function inspection_status_enum_methods_delegate_to_qc(): void
    {
        $inp = \Modules\Inspection\Support\Enums\InspectionStatus::class;
        $qc  = \Modules\QualityControl\Support\Enums\InspectionStatus::class;

        $statuses = $inp::all();

        foreach ($statuses as $status) {
            $this->assertSame(
                $qc::label($status),
                $inp::label($status),
                "Label mismatch for status '{$status}'."
            );
            $this->assertSame(
                $qc::badgeClass($status),
                $inp::badgeClass($status),
                "Badge class mismatch for status '{$status}'."
            );
        }
    }

    /** @test */
    public function schedule_status_enum_constants_forward_to_qc_values(): void
    {
        $inp = \Modules\Inspection\Support\Enums\ScheduleStatus::class;
        $qc  = \Modules\QualityControl\Support\Enums\ScheduleStatus::class;

        $this->assertSame($qc::OPEN,     $inp::OPEN);
        $this->assertSame($qc::PENDING,  $inp::PENDING);
        $this->assertSame($qc::RESOLVED, $inp::RESOLVED);
        $this->assertSame($qc::CLOSED,   $inp::CLOSED);
    }

    /** @test */
    public function schedule_status_enum_methods_delegate_to_qc(): void
    {
        $inp = \Modules\Inspection\Support\Enums\ScheduleStatus::class;
        $qc  = \Modules\QualityControl\Support\Enums\ScheduleStatus::class;

        $this->assertSame($qc::all(), $inp::all());

        foreach ($qc::all() as $status) {
            $this->assertSame($qc::label($status),     $inp::label($status));
            $this->assertSame($qc::badgeClass($status), $inp::badgeClass($status));
        }
    }

    /** @test */
    public function recurring_frequency_enum_constants_forward_to_qc_values(): void
    {
        $inp = \Modules\Inspection\Support\Enums\RecurringFrequency::class;
        $qc  = \Modules\QualityControl\Support\Enums\RecurringFrequency::class;

        $this->assertSame($qc::DAILY,       $inp::DAILY);
        $this->assertSame($qc::WEEKLY,      $inp::WEEKLY);
        $this->assertSame($qc::BI_WEEKLY,   $inp::BI_WEEKLY);
        $this->assertSame($qc::MONTHLY,     $inp::MONTHLY);
        $this->assertSame($qc::QUARTERLY,   $inp::QUARTERLY);
        $this->assertSame($qc::HALF_YEARLY, $inp::HALF_YEARLY);
        $this->assertSame($qc::ANNUALLY,    $inp::ANNUALLY);
    }

    /** @test */
    public function recurring_frequency_enum_methods_delegate_to_qc(): void
    {
        $inp = \Modules\Inspection\Support\Enums\RecurringFrequency::class;
        $qc  = \Modules\QualityControl\Support\Enums\RecurringFrequency::class;

        $this->assertSame($qc::all(), $inp::all());

        foreach ($qc::all() as $freq) {
            $this->assertSame($qc::label($freq),     $inp::label($freq));
            $this->assertSame($qc::badgeClass($freq), $inp::badgeClass($freq));
        }
    }

    /** @test */
    public function inspection_permissions_constants_forward_to_qc_legacy_constants(): void
    {
        $inp = \Modules\Inspection\Support\InspectionPermissions::class;
        $qc  = \Modules\QualityControl\Support\InspectionPermissions::class;

        $this->assertSame($qc::LEGACY_VIEW,               $inp::VIEW);
        $this->assertSame($qc::LEGACY_CREATE,             $inp::CREATE);
        $this->assertSame($qc::LEGACY_UPDATE,             $inp::UPDATE);
        $this->assertSame($qc::LEGACY_DELETE,             $inp::DELETE);
        $this->assertSame($qc::LEGACY_ADD_INSPECTION,     $inp::ADD_INSPECTION);
        $this->assertSame($qc::LEGACY_VIEW_INSPECTION,    $inp::VIEW_INSPECTION);
        $this->assertSame($qc::LEGACY_EDIT_INSPECTION,    $inp::EDIT_INSPECTION);
        $this->assertSame($qc::LEGACY_DELETE_INSPECTION,  $inp::DELETE_INSPECTION);
        $this->assertSame($qc::LEGACY_VIEW_INSPECTIONS,   $inp::VIEW_INSPECTIONS);
        $this->assertSame($qc::LEGACY_CREATE_INSPECTION,  $inp::CREATE_INSPECTION);
        $this->assertSame($qc::LEGACY_APPROVE_INSPECTION, $inp::APPROVE_INSPECTION);
        $this->assertSame($qc::LEGACY_REQUEST_RECLEAN,    $inp::REQUEST_RECLEAN);
    }

    /** @test */
    public function inspection_permissions_canonical_for_helper_resolves_qc_permission(): void
    {
        $result = \Modules\Inspection\Support\InspectionPermissions::canonicalFor('view_inspection');

        $this->assertSame(
            \Modules\QualityControl\Support\InspectionPermissions::VIEW,
            $result,
            "canonicalFor('view_inspection') must return the canonical QC permission."
        );
    }

    /** @test */
    public function inspection_permissions_is_covered_by_qc_returns_true_for_legacy_permissions(): void
    {
        $legacyPermissions = [
            'view_inspection',
            'add_inspection',
            'edit_inspection',
            'delete_inspection',
            'inspection.view',
            'inspection.create',
            'inspection.update',
            'inspection.delete',
            'request_reclean',
            'approve_inspection',
        ];

        foreach ($legacyPermissions as $legacy) {
            $this->assertTrue(
                \Modules\Inspection\Support\InspectionPermissions::isCoveredByQc($legacy),
                "Legacy permission '{$legacy}' must be covered by a QC canonical permission."
            );
        }
    }

    /** @test */
    public function inspection_routes_constants_forward_to_qc_values(): void
    {
        $inp = \Modules\Inspection\Support\InspectionRoutes::class;
        $qc  = \Modules\QualityControl\Support\InspectionRoutes::class;

        $this->assertSame($qc::RECURRING_INDEX,  $inp::RECURRING_INDEX);
        $this->assertSame($qc::SCHEDULES_INDEX,  $inp::SCHEDULES_INDEX);
        $this->assertSame($qc::INSPECTIONS_INDEX, $inp::INSPECTIONS_INDEX);
    }

    // =========================================================================
    // 3. TEMPLATE PACK DELEGATION
    // =========================================================================

    /** @test */
    public function template_packs_default_packs_delegates_to_qc(): void
    {
        $inspectionPacks = \Modules\Inspection\Support\TemplatePacks::defaultPacks();
        $qcPacks         = \Modules\QualityControl\Support\TemplatePacks::defaultPacks();

        $this->assertSame(
            $qcPacks,
            $inspectionPacks,
            'Inspection\TemplatePacks::defaultPacks() must return the same result as QC\TemplatePacks::defaultPacks().'
        );
    }

    /** @test */
    public function template_packs_contain_expected_trades(): void
    {
        $packs = \Modules\Inspection\Support\TemplatePacks::defaultPacks();

        foreach (['builder', 'electrician', 'plumber', 'cleaner'] as $trade) {
            $this->assertArrayHasKey(
                $trade,
                $packs,
                "Template packs must contain trade '{$trade}'."
            );
            $this->assertIsArray($packs[$trade]);
            $this->assertNotEmpty($packs[$trade]);
        }
    }

    // =========================================================================
    // 4. MIGRATION RETIREMENT COVERAGE
    // =========================================================================

    /** @test */
    public function migration_retirement_stub_file_exists(): void
    {
        $migrationPath = module_path(
            'Inspection',
            'Database/Migrations/2026_04_21_000001_mark_inspection_standalone_tables_as_superseded.php'
        );

        $this->assertFileExists(
            $migrationPath,
            'The retirement stub migration must exist to document that inspections/inspection_items are superseded.'
        );
    }

    /** @test */
    public function inspections_table_migration_has_guards_against_overwriting_existing_tables(): void
    {
        // The original 2026_04_10_000001 migration must be guarded (has 'if hasTable' check).
        $migrationPath = module_path(
            'Inspection',
            'Database/Migrations/2026_04_10_000001_create_inspections_table.php'
        );

        if (!file_exists($migrationPath)) {
            $this->markTestSkipped('Migration 2026_04_10_000001 not present.');
        }

        $content = file_get_contents($migrationPath);
        $this->assertStringContainsString(
            'hasTable',
            $content,
            'The inspections table migration must guard against overwriting an existing table.'
        );
    }

    // =========================================================================
    // 5. MANIFEST CONSISTENCY
    // =========================================================================

    /** @test */
    public function inspection_lifecycle_manifest_lists_all_scheduling_tables_as_qc_owned(): void
    {
        $manifest = include module_path('Inspection', 'manifests/lifecycle.php');

        $expectedQcOwned = [
            'inspection_schedules',
            'inspection_schedule_items',
            'inspection_schedule_recurring',
            'inspection_schedule_recurring_items',
            'inspection_templates',
            'inspection_template_items',
        ];

        foreach ($expectedQcOwned as $table) {
            $this->assertSame(
                'quality_control',
                $manifest['table_owner'][$table] ?? null,
                "Table '{$table}' must be declared as owned by 'quality_control' in Inspection lifecycle manifest."
            );
        }
    }

    /** @test */
    public function qc_lifecycle_manifest_owns_all_scheduling_tables(): void
    {
        $manifest = include module_path('QualityControl', 'manifests/lifecycle.php');

        $expectedOwned = [
            'inspection_schedules',
            'inspection_schedule_recurring',
            'inspection_templates',
            'qc_records',
        ];

        foreach ($expectedOwned as $table) {
            $this->assertContains(
                $table,
                $manifest['owner'] ?? [],
                "QC lifecycle manifest must list '{$table}' in the owned tables."
            );
        }
    }

    /** @test */
    public function inspection_signals_manifest_declares_qc_as_delegate(): void
    {
        $manifest = include module_path('Inspection', 'manifests/signals.php');

        $this->assertSame('quality_control', $manifest['delegate_to'] ?? null);
        $this->assertNotEmpty($manifest['emits'] ?? []);
        $this->assertNotEmpty($manifest['consumes'] ?? []);
    }

    /** @test */
    public function inspection_permissions_manifest_has_aliases_for_all_canonical_qc_permissions(): void
    {
        $manifest = include module_path('Inspection', 'manifests/permissions.php');

        $canonicalQcPermissions = [
            'view_quality_control',
            'add_quality_control',
            'edit_quality_control',
            'delete_quality_control',
            'trigger_reclean',
        ];

        foreach ($canonicalQcPermissions as $canonical) {
            $this->assertArrayHasKey(
                $canonical,
                $manifest['aliases'] ?? [],
                "Permissions manifest must have aliases for canonical QC permission '{$canonical}'."
            );
        }
    }

    /** @test */
    public function qc_lifecycle_manifest_role_is_verification_engine(): void
    {
        $manifest = include module_path('QualityControl', 'manifests/lifecycle.php');

        $this->assertSame('verification_engine', $manifest['role'] ?? null);
    }

    /** @test */
    public function inspection_lifecycle_manifest_role_is_planning(): void
    {
        $manifest = include module_path('Inspection', 'manifests/lifecycle.php');

        $this->assertSame('planning', $manifest['role'] ?? null);
    }

    // =========================================================================
    // 6. TABLE OWNERSHIP – SHARED TABLE NAMES
    // =========================================================================

    /** @test */
    public function all_scheduling_entity_pairs_share_the_same_table_name(): void
    {
        $pairs = [
            [
                new \Modules\QualityControl\Entities\Schedule(),
                new \Modules\Inspection\Entities\Schedule(),
                'Schedule',
            ],
            [
                new \Modules\QualityControl\Entities\RecurringSchedule(),
                new \Modules\Inspection\Entities\RecurringSchedule(),
                'RecurringSchedule',
            ],
            [
                new \Modules\QualityControl\Entities\ScheduleItems(),
                new \Modules\Inspection\Entities\ScheduleItems(),
                'ScheduleItems',
            ],
        ];

        foreach ($pairs as [$qcEntity, $inpEntity, $name]) {
            $this->assertSame(
                $qcEntity->getTable(),
                $inpEntity->getTable(),
                "QC and Inspection {$name} entities must use the same table."
            );
        }
    }

    /** @test */
    public function inspection_entities_are_subclasses_of_qc_entities(): void
    {
        $pairs = [
            [\Modules\Inspection\Entities\Schedule::class,           \Modules\QualityControl\Entities\Schedule::class],
            [\Modules\Inspection\Entities\RecurringSchedule::class,   \Modules\QualityControl\Entities\RecurringSchedule::class],
            [\Modules\Inspection\Entities\ScheduleItems::class,       \Modules\QualityControl\Entities\ScheduleItems::class],
            [\Modules\Inspection\Entities\InspectionTemplate::class,  \Modules\QualityControl\Entities\InspectionTemplate::class],
        ];

        foreach ($pairs as [$inspection, $qc]) {
            $this->assertTrue(
                is_subclass_of($inspection, $qc),
                "{$inspection} must be a subclass of {$qc}."
            );
        }
    }

    // =========================================================================
    // 7. DOMAIN ACTIONS EXISTENCE CHECK
    // =========================================================================

    /** @test */
    public function all_qc_scheduling_domain_actions_exist(): void
    {
        $actions = [
            \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionScheduleAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\RescheduleInspectionAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\AttachInspectionFileAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\SubmitInspectionReplyAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionTemplateAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\UpdateInspectionTemplateAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\CreateQcRecordAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\ScoreQcRecordAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\PassQcRecordAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\FailQcRecordAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\MarkNeedsRecleanAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\EscalateQcIssueAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\CloseQcRecordAction::class,
        ];

        foreach ($actions as $action) {
            $this->assertTrue(
                class_exists($action),
                "QC domain action must exist: {$action}"
            );
        }
    }

    /** @test */
    public function all_qc_application_queries_exist(): void
    {
        $queries = [
            \Modules\QualityControl\Application\Queries\ListInspectionSchedulesQuery::class,
            \Modules\QualityControl\Application\Queries\GetQcRecordDetailsQuery::class,
            \Modules\QualityControl\Application\Queries\ListRecleanRequestsQuery::class,
            \Modules\QualityControl\Application\Queries\GetInspectionTemplateQuery::class,
            \Modules\QualityControl\Application\Queries\GetQcDashboardQuery::class,
        ];

        foreach ($queries as $query) {
            $this->assertTrue(
                class_exists($query),
                "QC application query must exist: {$query}"
            );
        }
    }
}
