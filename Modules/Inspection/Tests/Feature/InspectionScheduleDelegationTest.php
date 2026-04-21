<?php

namespace Modules\Inspection\Tests\Feature;

use Tests\TestCase;

/**
 * Regression test: Inspection entity classes are thin delegates over QC entity classes.
 *
 * All schedule reads/writes resolve through QualityControl's entities and domain actions.
 * These tests guard against accidental reversion to the old standalone Inspection entities.
 */
class InspectionScheduleDelegationTest extends TestCase
{
    /** @test */
    public function inspection_schedule_entity_extends_qc_schedule(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Entities\Schedule::class,
                \Modules\QualityControl\Entities\Schedule::class
            ),
            'Modules\Inspection\Entities\Schedule must extend the QC Schedule entity.'
        );
    }

    /** @test */
    public function inspection_recurring_schedule_extends_qc_recurring_schedule(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Entities\RecurringSchedule::class,
                \Modules\QualityControl\Entities\RecurringSchedule::class
            ),
            'Modules\Inspection\Entities\RecurringSchedule must extend the QC RecurringSchedule entity.'
        );
    }

    /** @test */
    public function inspection_schedule_items_extends_qc_schedule_items(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Entities\ScheduleItems::class,
                \Modules\QualityControl\Entities\ScheduleItems::class
            ),
            'Modules\Inspection\Entities\ScheduleItems must extend the QC ScheduleItems entity.'
        );
    }

    /** @test */
    public function inspection_template_extends_qc_inspection_template(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Entities\InspectionTemplate::class,
                \Modules\QualityControl\Entities\InspectionTemplate::class
            ),
            'Modules\Inspection\Entities\InspectionTemplate must extend the QC InspectionTemplate entity.'
        );
    }

    /** @test */
    public function all_inspection_entities_share_qc_table_names(): void
    {
        $qcSchedule  = new \Modules\QualityControl\Entities\Schedule();
        $inpSchedule = new \Modules\Inspection\Entities\Schedule();

        $this->assertSame(
            $qcSchedule->getTable(),
            $inpSchedule->getTable(),
            'Both Schedule entities must use the same table.'
        );

        $qcRecurring  = new \Modules\QualityControl\Entities\RecurringSchedule();
        $inpRecurring = new \Modules\Inspection\Entities\RecurringSchedule();

        $this->assertSame(
            $qcRecurring->getTable(),
            $inpRecurring->getTable(),
            'Both RecurringSchedule entities must use the same table.'
        );

        $qcItems  = new \Modules\QualityControl\Entities\ScheduleItems();
        $inpItems = new \Modules\Inspection\Entities\ScheduleItems();

        $this->assertSame(
            $qcItems->getTable(),
            $inpItems->getTable(),
            'Both ScheduleItems entities must use the same table.'
        );
    }

    /** @test */
    public function inspection_controllers_are_subclasses_of_qc_controllers(): void
    {
        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\ScheduleController::class,
                \Modules\QualityControl\Http\Controllers\ScheduleController::class
            ),
            'Inspection ScheduleController must delegate to QC ScheduleController.'
        );

        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\ScheduleInspectionController::class,
                \Modules\QualityControl\Http\Controllers\ScheduleInspectionController::class
            ),
            'Inspection ScheduleInspectionController must delegate to QC ScheduleInspectionController.'
        );

        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\InspectionTemplateController::class,
                \Modules\QualityControl\Http\Controllers\InspectionTemplateController::class
            ),
            'Inspection InspectionTemplateController must delegate to QC InspectionTemplateController.'
        );

        $this->assertTrue(
            is_subclass_of(
                \Modules\Inspection\Http\Controllers\RecurringScheduleController::class,
                \Modules\QualityControl\Http\Controllers\RecurringScheduleController::class
            ),
            'Inspection RecurringScheduleController must delegate to QC RecurringScheduleController.'
        );
    }

    /** @test */
    public function qc_domain_actions_exist_for_all_scheduling_operations(): void
    {
        $actions = [
            \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionScheduleAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\RescheduleInspectionAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\AttachInspectionFileAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\SubmitInspectionReplyAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionTemplateAction::class,
            \Modules\QualityControl\Domain\Quality\Actions\UpdateInspectionTemplateAction::class,
        ];

        foreach ($actions as $action) {
            $this->assertTrue(
                class_exists($action),
                "Domain action must exist: $action"
            );
        }
    }

    /** @test */
    public function lifecycle_manifest_declares_qc_as_table_owner(): void
    {
        $manifest = include module_path('Inspection', 'manifests/lifecycle.php');

        $this->assertSame('quality_control', $manifest['table_owner']['inspection_schedules'] ?? null);
        $this->assertSame('quality_control', $manifest['table_owner']['inspection_templates'] ?? null);
    }

    /** @test */
    public function inspection_recurring_schedule_preserves_legacy_module_name(): void
    {
        // The Inspection bridge's RecurringSchedule must still seed under 'inspection'
        // so that existing module_settings rows are not orphaned.
        $this->assertSame(
            'inspection',
            \Modules\Inspection\Entities\RecurringSchedule::MODULE_NAME,
            'RecurringSchedule bridge must keep MODULE_NAME=inspection for module_settings compatibility.'
        );
    }
}
