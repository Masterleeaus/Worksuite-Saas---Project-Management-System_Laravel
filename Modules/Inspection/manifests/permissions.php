<?php

/**
 * Inspection module permissions manifest.
 *
 * Inspection permissions are bridge aliases for the canonical QualityControl
 * permissions. The permission check flow is:
 *   1. Check for canonical QC permission (e.g. view_quality_control)
 *   2. Fall back to legacy inspection alias (e.g. inspection.view, view_inspection)
 *
 * This manifest documents all aliases; enforcement lives in:
 *   Modules\QualityControl\Support\InspectionPermissions::aliasesFor()
 *   Modules\QualityControl\Support\ModuleAccess::can()
 */
return [
    'module' => 'inspection',
    'role'   => 'planning',

    /**
     * Permissions originating in this module (legacy Worksuite permissions).
     * These are registered in the DB but checked via the QC bridge.
     */
    'owns' => [
        'add_inspection',
        'view_inspection',
        'edit_inspection',
        'delete_inspection',
        'view_inspections',
        'create_inspection',
        'approve_inspection',
        'request_reclean',
    ],

    /**
     * Canonical QC permission → inspection alias mappings.
     * Kept in sync with Modules\QualityControl\Support\InspectionPermissions::MAP.
     */
    'aliases' => [
        'view_quality_control' => ['inspection.view', 'view_inspection', 'view_inspections'],
        'add_quality_control'  => ['inspection.create', 'add_inspection', 'create_inspection'],
        'edit_quality_control' => ['inspection.update', 'edit_inspection', 'approve_inspection'],
        'delete_quality_control' => ['inspection.delete', 'delete_inspection'],
        'trigger_reclean'      => ['request_reclean', 'approve_inspection'],
        'manage_qc_templates'  => ['inspection.create', 'inspection.update', 'create_inspection', 'edit_inspection'],
    ],
];
