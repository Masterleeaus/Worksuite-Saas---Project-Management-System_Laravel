<?php

namespace Modules\Inspection\Support\Enums;

/**
 * @deprecated Use Modules\QualityControl\Support\Enums\InspectionStatus (canonical source).
 *
 * This class is a compatibility bridge. All methods and constant values are identical to
 * the QC enum; static methods delegate to the canonical class to avoid dual maintenance.
 */
final class InspectionStatus
{
    /**
     * Shared status constants – must stay in sync with QC canonical values.
     * @see \Modules\QualityControl\Support\Enums\InspectionStatus
     */
    public const PENDING        = \Modules\QualityControl\Support\Enums\InspectionStatus::PENDING;
    public const IN_PROGRESS    = \Modules\QualityControl\Support\Enums\InspectionStatus::IN_PROGRESS;
    public const PASSED         = \Modules\QualityControl\Support\Enums\InspectionStatus::PASSED;
    public const FAILED         = \Modules\QualityControl\Support\Enums\InspectionStatus::FAILED;
    public const RECLEAN_BOOKED = \Modules\QualityControl\Support\Enums\InspectionStatus::RECLEAN_BOOKED;

    /** Returns only the 5 statuses originally owned by the Inspection module. */
    public static function all(): array
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::PASSED,
            self::FAILED,
            self::RECLEAN_BOOKED,
        ];
    }

    /** @deprecated Delegates to QC canonical implementation. */
    public static function label(string $status): string
    {
        return \Modules\QualityControl\Support\Enums\InspectionStatus::label($status);
    }

    /** @deprecated Delegates to QC canonical implementation. */
    public static function badgeClass(string $status): string
    {
        return \Modules\QualityControl\Support\Enums\InspectionStatus::badgeClass($status);
    }
}
