<?php

namespace Modules\Inspection\Support\Enums;

final class InspectionStatus
{
    public const PENDING    = 'pending';
    public const IN_PROGRESS = 'in_progress';
    public const PASSED     = 'passed';
    public const FAILED     = 'failed';
    public const RECLEAN_BOOKED = 'reclean_booked';

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

    public static function label(string $status): string
    {
        return match ($status) {
            self::PENDING        => 'Pending',
            self::IN_PROGRESS    => 'In Progress',
            self::PASSED         => 'Passed',
            self::FAILED         => 'Failed',
            self::RECLEAN_BOOKED => 'Re-clean Booked',
            default              => ucfirst($status),
        };
    }

    public static function badgeClass(string $status): string
    {
        return match ($status) {
            self::PENDING        => 'badge-secondary',
            self::IN_PROGRESS    => 'badge-info',
            self::PASSED         => 'badge-success',
            self::FAILED         => 'badge-danger',
            self::RECLEAN_BOOKED => 'badge-warning',
            default              => 'badge-light',
        };
    }
}
