<?php

namespace Modules\Inspection\Policies;

/**
 * @deprecated Compatibility bridge policy.
 *
 * All Inspection permission checks are owned by QualityControl.
 * This class extends the canonical QC InspectionPolicy so that
 * Inspection-namespaced policy lookups resolve with identical rules
 * without duplicating authorization logic.
 *
 * @see \Modules\QualityControl\Policies\InspectionPolicy
 */
class InspectionPolicy extends \Modules\QualityControl\Policies\InspectionPolicy
{
    // No overrides. Inherits all authorization logic from QC canonical policy.
}
