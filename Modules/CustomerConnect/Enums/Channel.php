<?php

namespace Modules\CustomerConnect\Enums;

/**
 * ISSUE B FIX: Converted from final class with constants to PHP 8.1 backed enum.
 * Consistent with CampaignStatus, DeliveryStatus, RunStatus patterns in this module.
 *
 * Usage:
 *   Channel::Email->value  => 'email'
 *   Channel::from('sms')   => Channel::Sms
 *   Channel::cases()       => [Channel::Email, Channel::Sms, ...]
 *
 * Backwards compatibility: Channel::all() is retained.
 */
enum Channel: string
{
    case Email    = 'email';
    case Sms      = 'sms';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';

    /** @return string[] */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function label(string $value): string
    {
        return match ($value) {
            'email'    => 'Email',
            'sms'      => 'SMS',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            default    => ucfirst($value),
        };
    }
}
