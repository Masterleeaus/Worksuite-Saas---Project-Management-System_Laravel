<?php

namespace Modules\CustomerConnect\Services\Premium;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OtpService
{
    public function createOtp(int $contactId, string $channel, int $ttlMinutes = 10): string
    {
        $code = (string)random_int(100000, 999999);

        if (DB::getSchemaBuilder()->hasTable('customerconnect_contact_verifications')) {
            DB::table('customerconnect_contact_verifications')->insert([
                'contact_id' => $contactId,
                'channel' => $channel,
                'otp_code' => password_hash($code, PASSWORD_BCRYPT),
                'expires_at' => now()->addMinutes($ttlMinutes),
                'verified_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $code;
    }

    public function verifyOtp(int $contactId, string $channel, string $code): bool
    {
        if (!DB::getSchemaBuilder()->hasTable('customerconnect_contact_verifications')) return false;

        $row = DB::table('customerconnect_contact_verifications')
            ->where('contact_id', $contactId)
            ->where('channel', $channel)
            ->whereNull('verified_at')
            ->orderByDesc('id')
            ->first();

        if (!$row) return false;
        if ($row->expires_at && now()->greaterThan($row->expires_at)) return false;

        if (!password_verify($code, $row->otp_code)) return false;

        DB::table('customerconnect_contact_verifications')->where('id', $row->id)->update([
            'verified_at' => now(),
            'updated_at' => now(),
        ]);

        // mark contact verified flags if exist
        if (DB::getSchemaBuilder()->hasColumn('customerconnect_contacts', $channel.'_verified_at')) {
            DB::table('customerconnect_contacts')->where('id', $contactId)->update([
                $channel.'_verified_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return true;
    }
}
