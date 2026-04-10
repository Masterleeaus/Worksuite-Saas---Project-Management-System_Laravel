<?php

namespace Modules\CustomerConnect\Services\Premium;

class PhoneFormatter
{
    /**
     * Best-effort E.164 normalization.
     * - Accepts numbers with spaces, dashes, parentheses.
     * - If already starts with +, keeps + and digits.
     * - If starts with 0 and defaultCountryCode provided (e.g., 61), converts to +<cc><rest>.
     */
    public function toE164(?string $raw, ?string $defaultCountryCode = null): ?string
    {
        if (!$raw) return null;

        $raw = trim($raw);
        // Remove common formatting
        $clean = preg_replace('/[^0-9+]/', '', $raw);

        if ($clean === '' ) return null;

        if (str_starts_with($clean, '+')) {
            $digits = '+' . preg_replace('/[^0-9]/', '', substr($clean, 1));
            return strlen($digits) > 1 ? $digits : null;
        }

        // If starts with 00 international
        if (str_starts_with($clean, '00')) {
            $digits = '+' . preg_replace('/[^0-9]/', '', substr($clean, 2));
            return strlen($digits) > 1 ? $digits : null;
        }

        // Local style
        $digitsOnly = preg_replace('/[^0-9]/', '', $clean);

        if ($defaultCountryCode && str_starts_with($digitsOnly, '0')) {
            $digitsOnly = ltrim($digitsOnly, '0');
            return '+' . $defaultCountryCode . $digitsOnly;
        }

        // Fallback: assume already country+number (rare)
        return $digitsOnly ? ('+' . $digitsOnly) : null;
    }
}
