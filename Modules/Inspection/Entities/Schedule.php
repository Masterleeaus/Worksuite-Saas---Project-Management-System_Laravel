<?php

namespace Modules\Inspection\Entities;

/**
 * @deprecated Compatibility bridge. Canonical schedule ownership lives in QualityControl.
 *
 * All schedule reads/writes now resolve through QualityControl's Schedule entity,
 * which owns the `inspection_schedules` table. This class is a thin alias kept
 * so that legacy code referencing Modules\Inspection\Entities\Schedule continues
 * to type-check and receive full model behaviour from the QC scheduling engine.
 */
class Schedule extends \Modules\QualityControl\Entities\Schedule
{
    //
}
