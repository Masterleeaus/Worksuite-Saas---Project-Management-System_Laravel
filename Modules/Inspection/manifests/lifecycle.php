<?php

/**
 * Inspection module lifecycle manifest.
 *
 * The Inspection module owns the PLANNING phase of the QA lifecycle.
 * Verification and scoring are delegated to QualityControl.
 * Escalation is delegated to Complaint.
 *
 * Three-module QA architecture:
 *   Inspection  → planning  (schedule creation, recurring scheduling, template selection)
 *   QualityControl → verification engine  (scoring, pass/fail, corrective actions)
 *   Complaint   → escalation engine  (complaint creation, SLA, resolution)
 */
return [
    'module'   => 'inspection',
    'role'     => 'planning',

    /**
     * The canonical owner of each table in the QA lifecycle.
     * All scheduling entities now resolve through QualityControl.
     */
    'table_owner' => [
        'inspection_schedules'           => 'quality_control',
        'inspection_schedule_items'      => 'quality_control',
        'inspection_schedule_recurring'  => 'quality_control',
        'inspection_schedule_recurring_items' => 'quality_control',
        'inspection_schedule_replies'    => 'quality_control',
        'inspection_schedule_files'      => 'quality_control',
        'inspection_templates'           => 'quality_control',
        'inspection_template_items'      => 'quality_control',
    ],

    /**
     * Lifecycle phases that originate in this module.
     * Each phase maps to the module that owns the transition action.
     */
    'phases' => [
        'schedule_created'     => 'inspection',
        'schedule_assigned'    => 'inspection',
        'inspection_started'   => 'inspection',
        'inspection_scored'    => 'quality_control',
        'inspection_passed'    => 'quality_control',
        'inspection_failed'    => 'quality_control',
        'corrective_assigned'  => 'quality_control',
        'corrective_overdue'   => 'quality_control',
        'complaint_created'    => 'complaint',
        'complaint_escalated'  => 'complaint',
        'complaint_resolved'   => 'complaint',
    ],

    /**
     * Domain actions that process transitions originating here.
     * All scheduling actions resolve through QualityControl domain actions.
     */
    'actions' => [
        'create_schedule'    => \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionScheduleAction::class,
        'reschedule'         => \Modules\QualityControl\Domain\Quality\Actions\RescheduleInspectionAction::class,
        'attach_file'        => \Modules\QualityControl\Domain\Quality\Actions\AttachInspectionFileAction::class,
        'submit_reply'       => \Modules\QualityControl\Domain\Quality\Actions\SubmitInspectionReplyAction::class,
        'create_template'    => \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionTemplateAction::class,
        'update_template'    => \Modules\QualityControl\Domain\Quality\Actions\UpdateInspectionTemplateAction::class,
    ],

    /**
     * Compatibility bridge status.
     */
    'bridge' => [
        'entity_delegates'   => true,
        'controller_bridges' => true,
        'route_backward_compat' => true,
        'migration_ownership' => 'quality_control',
    ],
];
