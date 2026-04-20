<?php

namespace Modules\Inspection\Support;

final class InspectionPermissions
{
    /**
     * @deprecated Use Modules\QualityControl\Support\InspectionPermissions.
     */
    public const VIEW   = 'inspection.view';
    /**
     * @deprecated Use Modules\QualityControl\Support\InspectionPermissions.
     */
    public const CREATE = 'inspection.create';
    /**
     * @deprecated Use Modules\QualityControl\Support\InspectionPermissions.
     */
    public const UPDATE = 'inspection.update';
    /**
     * @deprecated Use Modules\QualityControl\Support\InspectionPermissions.
     */
    public const DELETE = 'inspection.delete';

    public const ADD_INSPECTION = 'add_inspection';
    public const VIEW_INSPECTION = 'view_inspection';
    public const EDIT_INSPECTION = 'edit_inspection';
    public const DELETE_INSPECTION = 'delete_inspection';
    public const VIEW_INSPECTIONS = 'view_inspections';
    public const CREATE_INSPECTION = 'create_inspection';
    public const APPROVE_INSPECTION = 'approve_inspection';
    public const REQUEST_RECLEAN = 'request_reclean';
}
