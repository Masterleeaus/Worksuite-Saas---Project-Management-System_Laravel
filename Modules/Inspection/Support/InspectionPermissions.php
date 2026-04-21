<?php

namespace Modules\Inspection\Support;

/**
 * @deprecated Use Modules\QualityControl\Support\InspectionPermissions (canonical source).
 *
 * This class is kept for backward compatibility only. All constants forward their values
 * to the canonical QC class. Permission checking must always go through:
 *   Modules\QualityControl\Support\ModuleAccess::can()
 */
final class InspectionPermissions
{
    // -----------------------------------------------------------------------
    // Legacy v1 permission slugs (dot-notation). Forwarded from QC class.
    // -----------------------------------------------------------------------

    /** @deprecated */
    public const VIEW   = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_VIEW;
    /** @deprecated */
    public const CREATE = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_CREATE;
    /** @deprecated */
    public const UPDATE = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_UPDATE;
    /** @deprecated */
    public const DELETE = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_DELETE;

    // -----------------------------------------------------------------------
    // Legacy v2 permission slugs (underscore-notation). Forwarded from QC class.
    // -----------------------------------------------------------------------

    /** @deprecated */
    public const ADD_INSPECTION     = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_ADD_INSPECTION;
    /** @deprecated */
    public const VIEW_INSPECTION    = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_VIEW_INSPECTION;
    /** @deprecated */
    public const EDIT_INSPECTION    = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_EDIT_INSPECTION;
    /** @deprecated */
    public const DELETE_INSPECTION  = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_DELETE_INSPECTION;
    /** @deprecated */
    public const VIEW_INSPECTIONS   = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_VIEW_INSPECTIONS;
    /** @deprecated */
    public const CREATE_INSPECTION  = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_CREATE_INSPECTION;
    /** @deprecated */
    public const APPROVE_INSPECTION = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_APPROVE_INSPECTION;
    /** @deprecated */
    public const REQUEST_RECLEAN    = \Modules\QualityControl\Support\InspectionPermissions::LEGACY_REQUEST_RECLEAN;

    // -----------------------------------------------------------------------
    // Delegation helpers
    // -----------------------------------------------------------------------

    /**
     * Return the canonical QC permission string for a given legacy Inspection permission.
     *
     * @deprecated Use Modules\QualityControl\Support\InspectionPermissions::aliasesFor() directly.
     */
    public static function canonicalFor(string $legacyPermission): ?string
    {
        $map = \Modules\QualityControl\Support\InspectionPermissions::MAP;

        foreach ($map as $canonical => $aliases) {
            if (in_array($legacyPermission, $aliases, true)) {
                return $canonical;
            }
        }

        return null;
    }

    /**
     * Check whether a legacy Inspection permission is covered by any canonical QC permission.
     *
     * @deprecated Use Modules\QualityControl\Support\ModuleAccess::can() directly.
     */
    public static function isCoveredByQc(string $legacyPermission): bool
    {
        return self::canonicalFor($legacyPermission) !== null;
    }
}
