<?php

namespace Modules\CustomerConnect\Enums;

/**
 * ISSUE B FIX: Converted from final class with constants to PHP 8.1 backed enum.
 *
 * Usage:
 *   StepType::Send->value  => 'send'
 *   StepType::from('wait') => StepType::Wait
 *   StepType::cases()      => [StepType::Send, StepType::Wait, ...]
 */
enum StepType: string
{
    case Send      = 'send';
    case Wait      = 'wait';
    case Condition = 'condition';
    case Stop      = 'stop';

    /** @return string[] */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function label(string $value): string
    {
        return match ($value) {
            'send'      => 'Send Message',
            'wait'      => 'Wait / Delay',
            'condition' => 'Condition',
            'stop'      => 'Stop',
            default     => ucfirst($value),
        };
    }
}
