<?php

namespace App\Utils;

use Carbon\Carbon;

class Util
{
    public function setAndGetReferenceCount($refType, $businessId): int
    {
        return (int) now()->format('YmdHis');
    }

    public function generateReferenceNumber($refType, $refCount, $businessId = null, $prefix = null): string
    {
        $effectivePrefix = $prefix ?: strtoupper(substr((string) $refType, 0, 3));

        return $effectivePrefix . '-' . str_pad((string) $refCount, 6, '0', STR_PAD_LEFT);
    }

    public function uf_date($date, $time = false): ?string
    {
        if (empty($date)) {
            return null;
        }

        return Carbon::parse($date)->format($time ? 'Y-m-d H:i:s' : 'Y-m-d');
    }

    public function num_uf($number): float|int
    {
        if ($number === null || $number === '') {
            return 0;
        }

        $normalized = preg_replace('/[^0-9.\-]/', '', (string) $number);

        return str_contains($normalized, '.') ? (float) $normalized : (int) $normalized;
    }

    public function format_date($date, $showTime = false): ?string
    {
        if (empty($date)) {
            return null;
        }

        return Carbon::parse($date)->format($showTime ? 'Y-m-d H:i' : 'Y-m-d');
    }
}
