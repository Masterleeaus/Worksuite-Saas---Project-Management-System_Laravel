<?php

/**
 * QualityControl module lifecycle manifest.
 *
 * Three-module QA architecture:
 *
 *   Inspection  → PLANNING phase
 *       Owns: schedule creation, recurring scheduling, template selection, file attachment
 *       Entity delegates: Inspection entities extend QC entities (bridge pattern)
 *
 *   QualityControl → VERIFICATION ENGINE
 *       Owns: scoring, pass/fail determination, corrective actions, SLA enforcement
 *       Dispatches: QcFailedEvent, QcSeverityExceededEvent, CorrectiveAction* events,
 *                   VerificationScheduled/Completed events
 *
 *   Complaint → ESCALATION ENGINE
 *       Owns: complaint creation, management, SLA tracking, resolution
 *       Linked to QC via: qc_record_id on complaint rows
 *
 * All scheduling actions resolve through Domain\Quality\Actions\*.
 */
return [
    'module' => 'quality_control',
    'role'   => 'verification_engine',

    /**
     * Tables canonically owned by QualityControl.
     * Inspection module entities are thin delegates pointing at these tables.
     */
    'owner' => [
        'inspection_templates',
        'inspection_template_items',
        'inspection_schedules',
        'inspection_schedule_items',
        'inspection_schedule_recurring',
        'inspection_schedule_recurring_items',
        'inspection_schedule_replies',
        'inspection_schedule_files',
        'qc_records',
        'qc_record_items',
        'qc_corrective_actions',
        'qc_followup_verifications',
    ],

    /**
     * Three-module lifecycle phases with module ownership and action class.
     */
    'phases' => [
        // PHASE 1: PLANNING (owned by Inspection module)
        'schedule_created' => [
            'owner'  => 'inspection',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionScheduleAction::class,
        ],
        'schedule_rescheduled' => [
            'owner'  => 'inspection',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\RescheduleInspectionAction::class,
        ],
        'template_applied' => [
            'owner'  => 'inspection',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\CreateInspectionTemplateAction::class,
        ],

        // PHASE 2: VERIFICATION (owned by QualityControl)
        'record_created' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\CreateQcRecordAction::class,
        ],
        'record_scored' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\ScoreQcRecordAction::class,
        ],
        'record_passed' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\PassQcRecordAction::class,
        ],
        'record_failed' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\FailQcRecordAction::class,
        ],
        'reclean_triggered' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\MarkNeedsRecleanAction::class,
        ],
        'issue_escalated' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\EscalateQcIssueAction::class,
        ],
        'record_closed' => [
            'owner'  => 'quality_control',
            'action' => \Modules\QualityControl\Domain\Quality\Actions\CloseQcRecordAction::class,
        ],

        // PHASE 3: ESCALATION (owned by Complaint, triggered from QualityControl)
        'complaint_auto_created' => [
            'owner'   => 'quality_control',
            'action'  => \Modules\QualityControl\Domain\Quality\Actions\CreateComplaintFromQcFailureAction::class,
            'trigger' => 'severity_level >= qc_auto_create_complaint_threshold',
        ],
        'qc_from_complaint' => [
            'owner'   => 'quality_control',
            'action'  => \Modules\QualityControl\Domain\Quality\Actions\CreateQcFromComplaintAction::class,
            'trigger' => 'complaint.qc_followup_required = true',
        ],
    ],

    'compatibility' => [
        'inspection_bridge_module' => 'enabled',
        'entity_delegates'         => 'inspection_entities_extend_qc_entities',
        'controller_bridges'       => 'inspection_controllers_extend_qc_controllers',
        'route_backward_compat'    => 'inspection_routes_preserved',
    ],
];
