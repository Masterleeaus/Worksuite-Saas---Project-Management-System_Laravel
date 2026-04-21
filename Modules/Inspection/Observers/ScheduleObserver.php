<?php

namespace Modules\Inspection\Observers;

use Modules\Inspection\Entities\Schedule;

/**
 * @deprecated Compatibility bridge observer.
 *
 * All schedule lifecycle mutations are owned by QualityControl.
 * This class extends the canonical QC ScheduleObserver so that
 * Inspection\Entities\Schedule model events receive identical
 * processing without duplicating business logic.
 *
 * Registered in Inspection\Providers\EventServiceProvider for
 * Modules\Inspection\Entities\Schedule (the bridge entity class).
 * The canonical QC observer is registered separately for
 * Modules\QualityControl\Entities\Schedule.
 */
class ScheduleObserver extends \Modules\QualityControl\Observers\ScheduleObserver
{
    // No overrides. Inherits all lifecycle logic from QC canonical observer.
}
