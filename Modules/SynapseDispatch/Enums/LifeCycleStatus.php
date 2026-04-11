<?php

namespace Modules\SynapseDispatch\Enums;

enum LifeCycleStatus: string
{
    case CREATED   = 'created';
    case EN_ROUTE  = 'en_route';
    case STARTED   = 'started';
    case FINISHED  = 'finished';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::CREATED   => 'Created',
            self::EN_ROUTE  => 'En Route',
            self::STARTED   => 'Started',
            self::FINISHED  => 'Finished',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::CREATED   => 'bg-secondary',
            self::EN_ROUTE  => 'bg-info',
            self::STARTED   => 'bg-warning text-dark',
            self::FINISHED  => 'bg-success',
            self::CANCELLED => 'bg-danger',
        };
    }
}
