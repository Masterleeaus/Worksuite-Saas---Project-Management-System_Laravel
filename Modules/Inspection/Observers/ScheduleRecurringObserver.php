<?php

namespace Modules\Inspection\Observers;

/**
 * @deprecated Compatibility bridge observer.
 *
 * All recurring-schedule lifecycle mutations are owned by QualityControl.
 * This class extends the canonical QC ScheduleRecurringObserver so that
 * Inspection\Entities\RecurringSchedule model events receive identical
 * processing without duplicating business logic.
 *
 * Registered in Inspection\Providers\EventServiceProvider for
 * Modules\Inspection\Entities\RecurringSchedule (the bridge entity class).
 * The canonical QC observer is registered separately for
 * Modules\QualityControl\Entities\RecurringSchedule.
 */
class ScheduleRecurringObserver extends \Modules\QualityControl\Observers\ScheduleRecurringObserver
{
    // No overrides. Inherits all lifecycle logic from QC canonical observer.
}
