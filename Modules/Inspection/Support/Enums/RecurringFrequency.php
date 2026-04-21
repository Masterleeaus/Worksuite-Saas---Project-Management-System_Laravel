<?php

namespace Modules\Inspection\Support\Enums;

/**
 * @deprecated Use Modules\QualityControl\Support\Enums\RecurringFrequency (canonical source).
 *
 * Constants are forwarded from the QC enum. Do not add values here; add them in QC.
 */
final class RecurringFrequency
{
    public const DAILY       = \Modules\QualityControl\Support\Enums\RecurringFrequency::DAILY;
    public const WEEKLY      = \Modules\QualityControl\Support\Enums\RecurringFrequency::WEEKLY;
    public const BI_WEEKLY   = \Modules\QualityControl\Support\Enums\RecurringFrequency::BI_WEEKLY;
    public const MONTHLY     = \Modules\QualityControl\Support\Enums\RecurringFrequency::MONTHLY;
    public const QUARTERLY   = \Modules\QualityControl\Support\Enums\RecurringFrequency::QUARTERLY;
    public const HALF_YEARLY = \Modules\QualityControl\Support\Enums\RecurringFrequency::HALF_YEARLY;
    public const ANNUALLY    = \Modules\QualityControl\Support\Enums\RecurringFrequency::ANNUALLY;

    /** @deprecated Delegates to QC canonical implementation. */
    public static function all(): array
    {
        return \Modules\QualityControl\Support\Enums\RecurringFrequency::all();
    }

    /** @deprecated Delegates to QC canonical implementation. */
    public static function label(string $frequency): string
    {
        return \Modules\QualityControl\Support\Enums\RecurringFrequency::label($frequency);
    }

    /** @deprecated Delegates to QC canonical implementation. */
    public static function badgeClass(string $frequency): string
    {
        return \Modules\QualityControl\Support\Enums\RecurringFrequency::badgeClass($frequency);
    }
}
