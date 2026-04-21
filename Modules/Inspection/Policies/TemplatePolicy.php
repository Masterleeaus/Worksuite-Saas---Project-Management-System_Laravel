<?php

namespace Modules\Inspection\Policies;

/**
 * @deprecated Compatibility bridge policy.
 *
 * All template permission checks are owned by QualityControl.
 * This class extends the canonical QC TemplatePolicy so that
 * Inspection-namespaced policy lookups resolve with identical rules
 * without duplicating authorization logic.
 *
 * @see \Modules\QualityControl\Policies\TemplatePolicy
 */
class TemplatePolicy extends \Modules\QualityControl\Policies\TemplatePolicy
{
    // No overrides. Inherits all authorization logic from QC canonical policy.
}
