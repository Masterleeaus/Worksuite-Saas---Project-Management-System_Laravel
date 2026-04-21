<?php

namespace Modules\Inspection\Support\Enums;

/**
 * @deprecated Use Modules\QualityControl\Support\Enums\ScheduleStatus (canonical source).
 *
 * Constants are forwarded from the QC enum. Do not add values here; add them in QC.
 */
final class ScheduleStatus
{
    public const OPEN     = \Modules\QualityControl\Support\Enums\ScheduleStatus::OPEN;
    public const PENDING  = \Modules\QualityControl\Support\Enums\ScheduleStatus::PENDING;
    public const RESOLVED = \Modules\QualityControl\Support\Enums\ScheduleStatus::RESOLVED;
    public const CLOSED   = \Modules\QualityControl\Support\Enums\ScheduleStatus::CLOSED;

    /** @deprecated Delegates to QC canonical implementation. */
    public static function all(): array
    {
        return \Modules\QualityControl\Support\Enums\ScheduleStatus::all();
    }

    /** @deprecated Delegates to QC canonical implementation. */
    public static function label(string $status): string
    {
        return \Modules\QualityControl\Support\Enums\ScheduleStatus::label($status);
    }

    /** @deprecated Delegates to QC canonical implementation. */
    public static function badgeClass(string $status): string
    {
        return \Modules\QualityControl\Support\Enums\ScheduleStatus::badgeClass($status);
    }
}
