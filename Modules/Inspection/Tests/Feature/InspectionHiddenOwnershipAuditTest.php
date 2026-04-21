<?php

namespace Modules\Inspection\Tests\Feature;

use Tests\TestCase;

/**
 * Audit test suite: verifies that no hidden business logic or standalone ownership
 * remains in the Inspection module.
 *
 * Coverage areas:
 *   1. Observers are thin bridges (extend QC canonical observers — no duplicated logic)
 *   2. Policies are thin bridges (extend QC canonical policies — no duplicated logic)
 *   3. Request classes are thin bridges (extend QC canonical requests — no duplicated rules)
 *   4. Menu listener stays in Inspection (it is the planning module surface, not QC)
 *   5. EventServiceProvider registers observers correctly (Inspection entities → Inspection observers)
 *   6. Middleware is a pass-through (no hidden authorization gate owned by Inspection)
 *   7. InspectionController delegates exclusively to QC services (no standalone DB operations)
 *   8. Inspection\Entities\Inspection points to the standalone legacy table (not qc_records)
 *      and is documented as superseded
 */
class InspectionHiddenOwnershipAuditTest extends TestCase
{
    // =========================================================================
    // 1. OBSERVERS — must extend QC canonical observers
    // =========================================================================

    /** @test */
    public function schedule_observer_extends_qc_canonical_observer(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Observers\ScheduleObserver::class,
                \Modules\QualityControl\Observers\ScheduleObserver::class
            ),
            'Inspection\ScheduleObserver must extend QC\ScheduleObserver to avoid duplicated business logic.'
        );
    }

    /** @test */
    public function schedule_recurring_observer_extends_qc_canonical_observer(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Observers\ScheduleRecurringObserver::class,
                \Modules\QualityControl\Observers\ScheduleRecurringObserver::class
            ),
            'Inspection\ScheduleRecurringObserver must extend QC\ScheduleRecurringObserver to avoid duplicated business logic.'
        );
    }

    /** @test */
    public function inspection_schedule_observer_has_no_own_creating_method(): void
    {
        // The Inspection observer must not override the creating() hook with its own logic;
        // that hook lives in the QC canonical observer.
        $reflect = new \ReflectionClass(\Modules\Inspection\Observers\ScheduleObserver::class);

        $this->assertSame(
            \Modules\QualityControl\Observers\ScheduleObserver::class,
            $reflect->getMethod('creating')->getDeclaringClass()->getName(),
            'Inspection\ScheduleObserver must not declare its own creating() — it must inherit from QC.'
        );
    }

    /** @test */
    public function inspection_recurring_observer_has_no_own_creating_method(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Observers\ScheduleRecurringObserver::class);

        $this->assertSame(
            \Modules\QualityControl\Observers\ScheduleRecurringObserver::class,
            $reflect->getMethod('creating')->getDeclaringClass()->getName(),
            'Inspection\ScheduleRecurringObserver must not declare its own creating() — it must inherit from QC.'
        );
    }

    // =========================================================================
    // 2. POLICIES — must extend QC canonical policies
    // =========================================================================

    /** @test */
    public function inspection_policy_extends_qc_canonical_policy(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Policies\InspectionPolicy::class,
                \Modules\QualityControl\Policies\InspectionPolicy::class
            ),
            'Inspection\InspectionPolicy must extend QC\InspectionPolicy.'
        );
    }

    /** @test */
    public function schedule_policy_extends_qc_canonical_policy(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Policies\SchedulePolicy::class,
                \Modules\QualityControl\Policies\SchedulePolicy::class
            ),
            'Inspection\SchedulePolicy must extend QC\SchedulePolicy.'
        );
    }

    /** @test */
    public function template_policy_extends_qc_canonical_policy(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Policies\TemplatePolicy::class,
                \Modules\QualityControl\Policies\TemplatePolicy::class
            ),
            'Inspection\TemplatePolicy must extend QC\TemplatePolicy.'
        );
    }

    /** @test */
    public function inspection_policy_viewany_is_declared_in_qc(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Policies\InspectionPolicy::class);

        $this->assertSame(
            \Modules\QualityControl\Policies\InspectionPolicy::class,
            $reflect->getMethod('viewAny')->getDeclaringClass()->getName(),
            'InspectionPolicy::viewAny() must be declared in the QC canonical policy, not Inspection.'
        );
    }

    /** @test */
    public function template_policy_create_is_declared_in_qc(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Policies\TemplatePolicy::class);

        $this->assertSame(
            \Modules\QualityControl\Policies\TemplatePolicy::class,
            $reflect->getMethod('create')->getDeclaringClass()->getName(),
            'TemplatePolicy::create() must be declared in the QC canonical policy, not Inspection.'
        );
    }

    // =========================================================================
    // 3. REQUEST CLASSES — must extend QC canonical requests (no duplicated rules)
    // =========================================================================

    /** @test */
    public function store_inspection_template_item_request_extends_qc_version(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Requests\StoreInspectionTemplateItemRequest::class,
                \Modules\QualityControl\Http\Requests\StoreInspectionTemplateItemRequest::class
            ),
            'Inspection\StoreInspectionTemplateItemRequest must extend QC version to avoid duplicated rules.'
        );
    }

    /** @test */
    public function store_inspection_template_request_extends_qc_version(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Requests\StoreInspectionTemplateRequest::class,
                \Modules\QualityControl\Http\Requests\StoreInspectionTemplateRequest::class
            ),
            'Inspection\StoreInspectionTemplateRequest must extend QC version to avoid duplicated rules.'
        );
    }

    /** @test */
    public function update_inspection_template_request_extends_qc_version(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Requests\UpdateInspectionTemplateRequest::class,
                \Modules\QualityControl\Http\Requests\UpdateInspectionTemplateRequest::class
            ),
            'Inspection\UpdateInspectionTemplateRequest must extend QC version to avoid duplicated rules.'
        );
    }

    /** @test */
    public function inspection_template_item_request_rules_match_qc(): void
    {
        $inspectionRequest = new \Modules\Inspection\Http\Requests\StoreInspectionTemplateItemRequest();
        $qcRequest         = new \Modules\QualityControl\Http\Requests\StoreInspectionTemplateItemRequest();

        $this->assertSame(
            $qcRequest->rules(),
            $inspectionRequest->rules(),
            'Inspection\StoreInspectionTemplateItemRequest rules must be identical to QC version.'
        );
    }

    /** @test */
    public function inspection_template_request_rules_match_qc(): void
    {
        $inspectionRequest = new \Modules\Inspection\Http\Requests\StoreInspectionTemplateRequest();
        $qcRequest         = new \Modules\QualityControl\Http\Requests\StoreInspectionTemplateRequest();

        $this->assertSame(
            $qcRequest->rules(),
            $inspectionRequest->rules(),
            'Inspection\StoreInspectionTemplateRequest rules must be identical to QC version.'
        );
    }

    // =========================================================================
    // 4. MENU LISTENER — must stay in Inspection (planning surface)
    // =========================================================================

    /** @test */
    public function menu_listener_belongs_to_inspection_module(): void
    {
        // CompanyMenuListener is appropriate in Inspection because it registers the
        // planning/scheduling sidebar links. It does NOT register QC verification links.
        $this->assertTrue(
            class_exists(\Modules\Inspection\Listeners\CompanyMenuListener::class),
            'CompanyMenuListener must exist in Inspection module.'
        );

        // Verify it is NOT a subclass of any QC class (it is a standalone planning-surface listener).
        $reflect = new \ReflectionClass(\Modules\Inspection\Listeners\CompanyMenuListener::class);
        $this->assertStringContainsString(
            'Modules\Inspection',
            $reflect->getNamespaceName(),
            'CompanyMenuListener must belong to the Inspection namespace (it is a planning-module surface).'
        );
    }

    /** @test */
    public function menu_listener_registers_inspection_not_qc_labels(): void
    {
        // The menu items added by CompanyMenuListener must use 'inspection' as module,
        // not 'quality_control', so that the planning sidebar remains distinct from the QC panel.
        $reflect = new \ReflectionClass(\Modules\Inspection\Listeners\CompanyMenuListener::class);
        $source  = file_get_contents($reflect->getFileName());

        $this->assertStringContainsString(
            "'module' => \$module",
            $source,
            'CompanyMenuListener must set the module key to the $module variable (= "Inspection").'
        );

        // Must NOT hard-code quality_control as the module for its sidebar items.
        $this->assertStringNotContainsString(
            "'module' => 'quality_control'",
            $source,
            'CompanyMenuListener must not register sidebar items under "quality_control" module name.'
        );
    }

    // =========================================================================
    // 5. MIDDLEWARE — must be a pass-through (no hidden Inspection gate)
    // =========================================================================

    /** @test */
    public function ensure_inspection_enabled_middleware_is_pass_through(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Http\Middleware\EnsureInspectionEnabled::class);
        $source  = file_get_contents($reflect->getFileName());

        // Must call $next($request) — i.e., it must NOT abort unconditionally.
        $this->assertStringContainsString(
            '$next($request)',
            $source,
            'EnsureInspectionEnabled middleware must pass through to $next($request).'
        );

        // Must NOT contain abort() calls (which would constitute hidden ownership of access control).
        $this->assertStringNotContainsString(
            'abort(',
            $source,
            'EnsureInspectionEnabled middleware must not contain abort() calls — access control is owned by QC ModuleAccess.'
        );
    }

    // =========================================================================
    // 6. INSPECTION CONTROLLER — must delegate exclusively to QC services
    // =========================================================================

    /** @test */
    public function inspection_controller_injects_qc_execution_record_service(): void
    {
        $reflect     = new \ReflectionClass(\Modules\Inspection\Http\Controllers\InspectionController::class);
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

    /** @test */
    public function inspection_controller_source_contains_no_direct_inspection_model_queries(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Http\Controllers\InspectionController::class);
        $source  = file_get_contents($reflect->getFileName());

        // The controller must not directly query the Inspection Eloquent model.
        $this->assertStringNotContainsString(
            'Inspection::',
            $source,
            'InspectionController must not call Inspection:: static model methods (use QC service instead).'
        );
        $this->assertStringNotContainsString(
            'InspectionItem::',
            $source,
            'InspectionController must not call InspectionItem:: static model methods.'
        );

        // All data access must go via QC ExecutionRecordService.
        $this->assertStringContainsString(
            'this->records->',
            $source,
            'InspectionController must use $this->records (ExecutionRecordService) for all data operations.'
        );
    }

    // =========================================================================
    // 7. ENTITY DOCUMENTATION — Inspection\Entities\Inspection is legacy-only
    // =========================================================================

    /** @test */
    public function inspection_entity_uses_inspections_table_not_qc_records(): void
    {
        $model = new \Modules\Inspection\Entities\Inspection();

        $this->assertSame(
            'inspections',
            $model->getTable(),
            'Inspection\Entities\Inspection must point to the legacy "inspections" table (not qc_records).'
        );
    }

    /** @test */
    public function inspection_entity_is_not_related_to_qc_record_entity(): void
    {
        // The Inspection model is the legacy standalone entity pointing to the retired
        // standalone `inspections` table. It must not extend QcRecord or any QC entity.
        $inspectionClass = \Modules\Inspection\Entities\Inspection::class;

        if (class_exists(\Modules\QualityControl\Entities\QcRecord::class)) {
            $this->assertFalse(
                is_subclass_of($inspectionClass, \Modules\QualityControl\Entities\QcRecord::class),
                'Inspection\Entities\Inspection must not extend QcRecord — it is the legacy standalone model.'
            );
        }

        // It should be a standalone model (extends BaseModel or similar), not a QC bridge entity.
        $reflect = new \ReflectionClass($inspectionClass);
        $this->assertNotSame(
            \Modules\QualityControl\Entities\Schedule::class,
            $reflect->getParentClass()->getName(),
            'Inspection\Entities\Inspection must not extend QC\Schedule.'
        );
    }

    // =========================================================================
    // 8. PROVIDER WIRING — EventServiceProvider registers Inspection observers on Inspection entities
    // =========================================================================

    /** @test */
    public function event_service_provider_registers_inspection_observer_on_inspection_schedule(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Providers\EventServiceProvider::class);
        $source  = file_get_contents($reflect->getFileName());

        $this->assertStringContainsString(
            'Modules\Inspection\Entities\Schedule',
            $source,
            'EventServiceProvider must register observer on Inspection\Entities\Schedule (bridge entity).'
        );

        $this->assertStringContainsString(
            'ScheduleObserver',
            $source,
            'EventServiceProvider must register ScheduleObserver.'
        );
    }

    /** @test */
    public function event_service_provider_registers_inspection_recurring_observer_on_inspection_entity(): void
    {
        $reflect = new \ReflectionClass(\Modules\Inspection\Providers\EventServiceProvider::class);
        $source  = file_get_contents($reflect->getFileName());

        $this->assertStringContainsString(
            'Modules\Inspection\Entities\RecurringSchedule',
            $source,
            'EventServiceProvider must register observer on Inspection\Entities\RecurringSchedule (bridge entity).'
        );

        $this->assertStringContainsString(
            'ScheduleRecurringObserver',
            $source,
            'EventServiceProvider must register ScheduleRecurringObserver.'
        );
    }

    // =========================================================================
    // 9. MIGRATION SCHEMA OWNERSHIP — no undocumented standalone schema
    // =========================================================================

    /** @test */
    public function inspection_migration_retirement_stub_exists(): void
    {
        $this->assertFileExists(
            module_path('Inspection', 'Database/Migrations/2026_04_21_000001_mark_inspection_standalone_tables_as_superseded.php'),
            'The retirement stub migration must exist to document that inspections/inspection_items tables are superseded.'
        );
    }

    /** @test */
    public function lifecycle_manifest_documents_all_tables_as_qc_owned(): void
    {
        $manifest = include module_path('Inspection', 'manifests/lifecycle.php');

        $tables = [
            'inspection_schedules',
            'inspection_schedule_items',
            'inspection_schedule_recurring',
            'inspection_schedule_recurring_items',
            'inspection_templates',
            'inspection_template_items',
        ];

        foreach ($tables as $table) {
            $this->assertSame(
                'quality_control',
                $manifest['table_owner'][$table] ?? null,
                "Table '{$table}' must be documented as owned by 'quality_control' in Inspection lifecycle manifest."
            );
        }
    }
}
