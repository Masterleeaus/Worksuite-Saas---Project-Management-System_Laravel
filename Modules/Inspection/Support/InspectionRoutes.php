<?php

namespace Modules\Inspection\Support;

/**
 * @deprecated Use Modules\QualityControl\Support\InspectionRoutes (canonical source).
 *
 * These constants are kept for backward compatibility. They are identical to the QC class
 * values and are preserved so that existing Inspection views/controllers referencing this
 * class do not require changes.
 */
final class InspectionRoutes
{
    public const RECURRING_INDEX = \Modules\QualityControl\Support\InspectionRoutes::RECURRING_INDEX;
    public const SCHEDULES_INDEX = \Modules\QualityControl\Support\InspectionRoutes::SCHEDULES_INDEX;
    public const INSPECTIONS_INDEX = \Modules\QualityControl\Support\InspectionRoutes::INSPECTIONS_INDEX;
}
