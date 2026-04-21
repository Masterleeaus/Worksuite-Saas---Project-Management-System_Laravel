<?php

namespace Modules\Inspection\Policies;

/**
 * @deprecated Compatibility bridge policy.
 *
 * All schedule permission checks are owned by QualityControl.
 * This class extends the canonical QC SchedulePolicy so that
 * Inspection-namespaced policy lookups resolve with identical rules
 * without duplicating authorization logic.
 *
 * @see \Modules\QualityControl\Policies\SchedulePolicy
 */
class SchedulePolicy extends \Modules\QualityControl\Policies\SchedulePolicy
{
    // No overrides. Inherits all authorization logic from QC canonical policy.
}
