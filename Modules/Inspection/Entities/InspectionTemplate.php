<?php

namespace Modules\Inspection\Entities;

/**
 * @deprecated Compatibility bridge. Canonical template ownership lives in QualityControl.
 *
 * Templates are registered and managed via the QualityControl template registry.
 * This alias ensures legacy type-checks (`instanceof InspectionTemplate`) continue
 * to pass while the underlying entity is provided by the QC scheduling engine.
 */
class InspectionTemplate extends \Modules\QualityControl\Entities\InspectionTemplate
{
    //
}
