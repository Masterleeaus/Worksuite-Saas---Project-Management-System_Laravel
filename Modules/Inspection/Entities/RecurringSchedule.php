<?php

namespace Modules\Inspection\Entities;

/**
 * @deprecated Compatibility bridge. Canonical recurring-schedule ownership lives in QualityControl.
 *
 * All recurring schedule reads/writes now resolve through QualityControl's RecurringSchedule
 * entity, which owns the `inspection_schedule_recurring` table. This class is a thin alias
 * kept so that legacy code (observers, listeners, CompanyCreatedListener) continues to work.
 *
 * Note: addModuleSetting() is intentionally left here calling the parent implementation so that
 * the Inspection module's CompanyCreatedListener continues to register settings under the
 * 'inspection' module key (existing module_settings rows must not change module_name).
 */
class RecurringSchedule extends \Modules\QualityControl\Entities\RecurringSchedule
{
    /**
     * Override the parent's MODULE_NAME so that the Inspection bridge listener
     * continues to seed module_settings rows under the legacy 'inspection' key.
     */
    const MODULE_NAME = 'inspection';
}
