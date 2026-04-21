<?php

namespace Modules\Inspection\Entities;

/**
 * @deprecated Compatibility bridge. Canonical schedule-item ownership lives in QualityControl.
 *
 * Maps to the same `inspection_schedule_items` table as the QC counterpart.
 */
class ScheduleItems extends \Modules\QualityControl\Entities\ScheduleItems
{
    //
}
