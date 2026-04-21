<?php

namespace Modules\Inspection\Http\Requests;

/**
 * @deprecated Compatibility bridge request.
 *
 * Validation rules for inspection template items are owned by QualityControl.
 * This class extends the canonical QC request so that Inspection routes continue
 * to work without duplicating rule definitions.
 *
 * @see \Modules\QualityControl\Http\Requests\StoreInspectionTemplateItemRequest
 */
class StoreInspectionTemplateItemRequest extends \Modules\QualityControl\Http\Requests\StoreInspectionTemplateItemRequest
{
    // No overrides. Inherits all validation rules from QC canonical request.
}
