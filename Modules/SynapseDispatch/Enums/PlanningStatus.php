<?php

namespace Modules\SynapseDispatch\Enums;

enum PlanningStatus: string
{
    case UNPLANNED  = 'U';
    case PLANNED    = 'P';
    case DISPATCHED = 'D';
    case DECLINED   = 'X';
    case COMPLETED  = 'C';

    public function label(): string
    {
        return match($this) {
            self::UNPLANNED  => 'Unplanned',
            self::PLANNED    => 'Planned',
            self::DISPATCHED => 'Dispatched',
            self::DECLINED   => 'Declined',
            self::COMPLETED  => 'Completed',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::UNPLANNED  => 'bg-secondary',
            self::PLANNED    => 'bg-info',
            self::DISPATCHED => 'bg-primary',
            self::DECLINED   => 'bg-danger',
            self::COMPLETED  => 'bg-success',
        };
    }
}
