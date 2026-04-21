<?php

namespace Modules\Inspection\Http\Requests;

/**
 * @deprecated Compatibility bridge request.
 *
 * Validation rules for inspection templates are owned by QualityControl.
 * This class extends the canonical QC request so that Inspection routes continue
 * to work without duplicating rule definitions.
 *
 * @see \Modules\QualityControl\Http\Requests\StoreInspectionTemplateRequest
 */
class StoreInspectionTemplateRequest extends \Modules\QualityControl\Http\Requests\StoreInspectionTemplateRequest
{
    // No overrides. Inherits all validation rules from QC canonical request.
}
