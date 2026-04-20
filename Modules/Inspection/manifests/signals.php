<?php

/**
 * Inspection module signal manifest.
 *
 * This module emits planning-phase signals. All verification and escalation
 * signals are owned by QualityControl and Complaint respectively.
 * Signals listed here delegate their processing to QualityControl listeners.
 */
return [
    'emits' => [
        'inspection.schedule_created',
        'inspection.schedule_assigned',
        'inspection.schedule_recurring_triggered',
        'inspection.template_applied',
        'inspection.reply_submitted',
        'inspection.file_attached',
    ],

    'consumes' => [
        // Inspection listens for QC outcomes to update schedule status.
        'quality_control.record_passed',
        'quality_control.record_failed',
        'quality_control.reclean_triggered',
        'quality_control.needs_reclean',
    ],

    /**
     * Signal delegation map.
     * Signals emitted by Inspection are handled by QualityControl listeners.
     * This preserves the single-responsibility principle:
     *   Inspection emits → QC handles verification.
     */
    'delegate_to' => 'quality_control',
];
