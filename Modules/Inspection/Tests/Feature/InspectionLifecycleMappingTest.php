<?php

namespace Modules\Inspection\Tests\Feature;

use Tests\TestCase;

/**
 * Regression test: Inspection lifecycle values map correctly to QC canonical status catalog.
 *
 * Inspection module uses status values from the QC enum (InspectionStatus) which is now the
 * canonical source. Legacy status strings used by old Inspection code are mapped via
 * InspectionStatus::fromLegacy(). These tests guard the status mapping contract.
 */
class InspectionLifecycleMappingTest extends TestCase
{
    /** @test */
    public function qc_inspection_status_contains_all_legacy_inspection_statuses(): void
    {
        $qcStatus   = \Modules\QualityControl\Support\Enums\InspectionStatus::class;
        $inpStatus  = \Modules\Inspection\Support\Enums\InspectionStatus::class;

        // All statuses defined by the Inspection module must exist in the QC enum.
        foreach ($inpStatus::all() as $legacyStatus) {
            $this->assertContains(
                $legacyStatus,
                $qcStatus::all(),
                "QC InspectionStatus must include the legacy Inspection status '{$legacyStatus}'."
            );
        }
    }

    /** @test */
    public function qc_inspection_status_labels_match_inspection_module_labels(): void
    {
        $sharedStatuses = [
            \Modules\Inspection\Support\Enums\InspectionStatus::PENDING,
            \Modules\Inspection\Support\Enums\InspectionStatus::IN_PROGRESS,
            \Modules\Inspection\Support\Enums\InspectionStatus::PASSED,
            \Modules\Inspection\Support\Enums\InspectionStatus::FAILED,
            \Modules\Inspection\Support\Enums\InspectionStatus::RECLEAN_BOOKED,
        ];

        foreach ($sharedStatuses as $status) {
            $this->assertSame(
                \Modules\Inspection\Support\Enums\InspectionStatus::label($status),
                \Modules\QualityControl\Support\Enums\InspectionStatus::label($status),
                "Label mismatch for status '{$status}' between Inspection and QC enums."
            );
        }
    }

    /** @test */
    public function qc_inspection_status_badge_classes_match_inspection_module(): void
    {
        $sharedStatuses = [
            \Modules\Inspection\Support\Enums\InspectionStatus::PENDING,
            \Modules\Inspection\Support\Enums\InspectionStatus::PASSED,
            \Modules\Inspection\Support\Enums\InspectionStatus::FAILED,
        ];

        foreach ($sharedStatuses as $status) {
            $this->assertSame(
                \Modules\Inspection\Support\Enums\InspectionStatus::badgeClass($status),
                \Modules\QualityControl\Support\Enums\InspectionStatus::badgeClass($status),
                "Badge class mismatch for status '{$status}' between Inspection and QC enums."
            );
        }
    }

    /** @test */
    public function from_legacy_maps_inspection_outcomes_to_qc_statuses(): void
    {
        $qcStatus = \Modules\QualityControl\Support\Enums\InspectionStatus::class;

        $expectedMappings = [
            'pass'             => $qcStatus::PASSED,
            'passed'           => $qcStatus::PASSED,
            'approved'         => $qcStatus::PASSED,
            'fail'             => $qcStatus::FAILED,
            'failed'           => $qcStatus::FAILED,
            'rejected'         => $qcStatus::FAILED,
            'reclean_booked'   => $qcStatus::RECLEAN_BOOKED,
            'reclean_required' => $qcStatus::RECLEAN_REQUIRED,
            'needs_reclean'    => $qcStatus::RECLEAN_REQUIRED,
            'reclean_done'     => $qcStatus::RECLEAN_DONE,
            'recleaned'        => $qcStatus::RECLEAN_DONE,
            'in_progress'      => $qcStatus::IN_PROGRESS,
            'in-progress'      => $qcStatus::IN_PROGRESS,
            'closed'           => $qcStatus::CLOSED,
            'completed'        => $qcStatus::CLOSED,
            'complete'         => $qcStatus::CLOSED,
        ];

        foreach ($expectedMappings as $legacy => $expected) {
            $this->assertSame(
                $expected,
                $qcStatus::fromLegacy($legacy),
                "InspectionStatus::fromLegacy('{$legacy}') should return '{$expected}'."
            );
        }
    }

    /** @test */
    public function qc_schedule_status_has_canonical_values(): void
    {
        $status = \Modules\QualityControl\Support\Enums\ScheduleStatus::class;

        $this->assertSame('open',     $status::OPEN);
        $this->assertSame('pending',  $status::PENDING);
        $this->assertSame('resolved', $status::RESOLVED);
        $this->assertSame('closed',   $status::CLOSED);

        $this->assertContains('open',     $status::all());
        $this->assertContains('resolved', $status::all());
    }

    /** @test */
    public function permissions_manifest_bridges_to_qc_canonical_permissions(): void
    {
        $manifest = include module_path('Inspection', 'manifests/permissions.php');

        $this->assertSame('inspection', $manifest['module']);
        $this->assertArrayHasKey('aliases', $manifest);

        $aliases = $manifest['aliases'];

        $this->assertArrayHasKey('view_quality_control',   $aliases);
        $this->assertArrayHasKey('add_quality_control',    $aliases);
        $this->assertArrayHasKey('edit_quality_control',   $aliases);
        $this->assertArrayHasKey('delete_quality_control', $aliases);
        $this->assertArrayHasKey('trigger_reclean',        $aliases);

        // view_inspection must be a legacy alias for view_quality_control.
        $this->assertContains('view_inspection', $aliases['view_quality_control']);
    }

    /** @test */
    public function signals_manifest_declares_delegation_to_qc(): void
    {
        $manifest = include module_path('Inspection', 'manifests/signals.php');

        $this->assertArrayHasKey('emits', $manifest);
        $this->assertArrayHasKey('consumes', $manifest);
        $this->assertSame('quality_control', $manifest['delegate_to']);

        // QC outcome signals must be in the consumes list.
        $this->assertContains('quality_control.record_passed', $manifest['consumes']);
        $this->assertContains('quality_control.record_failed', $manifest['consumes']);
    }

    /** @test */
    public function qc_lifecycle_manifest_reflects_three_module_architecture(): void
    {
        $manifest = include module_path('QualityControl', 'manifests/lifecycle.php');

        $this->assertSame('quality_control',    $manifest['module']);
        $this->assertSame('verification_engine', $manifest['role']);
        $this->assertArrayHasKey('phases',       $manifest);

        // Inspect planning phase goes to Inspection.
        $this->assertSame('inspection', $manifest['phases']['schedule_created']['owner'] ?? null);

        // Verification phases go to QC.
        $this->assertSame('quality_control', $manifest['phases']['record_scored']['owner'] ?? null);
        $this->assertSame('quality_control', $manifest['phases']['record_failed']['owner'] ?? null);

        // Escalation originates from QC action.
        $this->assertSame('quality_control', $manifest['phases']['complaint_auto_created']['owner'] ?? null);
    }
}
