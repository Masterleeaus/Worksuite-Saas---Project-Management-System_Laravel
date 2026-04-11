<?php

namespace Modules\Biometric\Entities;

use App\Models\Attendance;
use App\Models\BaseModel;
use App\Models\EmployeeDetails;
use App\Models\User;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Biometric\Events\BiometricClockIn;
use Modules\Biometric\Services\GeofenceService;

class BiometricEmployee extends BaseModel
{
    use HasCompany;

    protected $guarded = ['id'];

    /**
     * Columns that are stored encrypted at rest.
     * Decryption is done transparently via the accessor/mutator pair below.
     */
    protected array $encryptedColumns = ['fingerprint_template'];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // -------------------------------------------------------------------------
    // Accessors / Mutators — transparent encryption for fingerprint template
    // -------------------------------------------------------------------------

    /**
     * Return the decrypted fingerprint template, or null if not set.
     */
    public function getFingerprintTemplateAttribute(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException) {
            // Value was stored before encryption was added — return as-is so
            // existing records are not lost.
            return $value;
        }
    }

    /**
     * Encrypt the fingerprint template before storing it.
     */
    public function setFingerprintTemplateAttribute(?string $value): void
    {
        $this->attributes['fingerprint_template'] = $value ? Crypt::encryptString($value) : null;
    }

    // -------------------------------------------------------------------------
    // Device data ingestion
    // -------------------------------------------------------------------------

    public static function recordFingerprint($rows, $device): void
    {
        foreach ($rows as $row) {
            if (empty($row)) {
                continue;
            }

            $parts = explode("\t", $row);
            Log::info('Parts: ' . json_encode($parts));

            if (count($parts) > 0) {
                self::processRow($parts, $device);
            }
        }
    }

    private static function processRow($parts, $device): void
    {
        if (str_starts_with($parts[0], 'FP PIN=')) {
            self::handleFingerprintData($parts, $device);
        } elseif (str_starts_with($parts[0], 'USER PIN=')) {
            self::handleUserData($parts, $device);
        } elseif (str_starts_with($parts[0], 'BIOPHOTO PIN=')) {
            self::handlePhotoData($parts, $device);
        }
    }

    private static function handleFingerprintData($parts, $device): void
    {
        $employeeId    = str_replace('FP PIN=', '', $parts[0]);
        $fingerprintId = null;
        $template      = null;

        foreach ($parts as $part) {
            if (str_starts_with($part, 'FID=')) {
                $fingerprintId = str_replace('FID=', '', $part);
            } elseif (str_starts_with($part, 'TMP=')) {
                $template = str_replace('TMP=', '', $part);
            }
        }

        Log::info('Fingerprint data received', [
            'device_serial_number' => $device->serial_number,
            'employee_id'          => $employeeId,
            // fingerprint_id and template are never logged — sensitive biometric data.
        ]);

        if ($employeeId && $fingerprintId) {
            // The mutator on `fingerprint_template` will encrypt the value.
            self::updateOrCreateBiometricEmployee(
                $employeeId,
                $device->company_id,
                [
                    'has_fingerprint'      => true,
                    'fingerprint_id'       => $fingerprintId,
                    'fingerprint_template' => $template,
                ]
            );
        }
    }

    private static function handleUserData($parts, $device): void
    {
        $employeeId = str_replace('USER PIN=', '', $parts[0]);
        $cardNumber = null;

        foreach ($parts as $part) {
            if (str_starts_with($part, 'Card=')) {
                $cardNumber = str_replace('Card=', '', $part);
            }
        }

        Log::info('User data received', [
            'employee_id' => $employeeId,
            // card_number intentionally omitted — treat as sensitive credential.
        ]);

        if ($employeeId && $cardNumber) {
            self::updateOrCreateBiometricEmployee(
                $employeeId,
                $device->company_id,
                ['card_number' => $cardNumber]
            );
        }
    }

    /**
     * Face recognition photos are stored on the **private** disk so they are
     * never publicly accessible.  Only a path is kept in the DB column.
     */
    private static function handlePhotoData($parts, $device): void
    {
        $employeeId = str_replace('BIOPHOTO PIN=', '', $parts[0]);
        $photoContent = null;

        foreach ($parts as $part) {
            if (str_starts_with($part, 'Content=')) {
                $photoContent = str_replace('Content=', '', $part);
            }
        }

        if (! $employeeId || ! $photoContent) {
            return;
        }

        // Store on the private disk — never the public disk.
        $path = 'biometric/photos/' . $device->company_id . '/' . $employeeId . '_' . time() . '.jpg';
        Storage::disk('local')->put($path, base64_decode($photoContent));

        Log::info('Bio-photo stored on private disk', [
            'employee_id' => $employeeId,
            'path'        => $path,
        ]);

        self::updateOrCreateBiometricEmployee(
            $employeeId,
            $device->company_id,
            [
                'has_photo' => true,
                'photo'     => $path, // Store path, NOT raw base64 content
            ]
        );
    }

    private static function updateOrCreateBiometricEmployee($employeeId, $companyId, $data): self
    {
        return self::updateOrCreate(
            [
                'biometric_employee_id' => $employeeId,
                'company_id'            => $companyId,
            ],
            $data
        );
    }

    // -------------------------------------------------------------------------
    // Attendance bridging — write to core `attendances` table
    // -------------------------------------------------------------------------

    /**
     * Process raw ZKTeco attendance rows, write to `biometric_device_attendances`
     * and bridge each record into the core `attendances` table.
     *
     * Also aliased as markAttendanceTodeviceAndApplication (lowercase 'd') so
     * the ZKTecoController call continues to resolve regardless of capitalisation.
     */
    public static function markAttendanceToDeviceAndApplication($rows, $device, $request): void
    {
        foreach ($rows as $line) {
            $parts = explode("\t", $line);

            Log::info('Parts: ' . json_encode($parts));

            if (count($parts) < 2) {
                continue;
            }

            $deviceEmployeeId = $parts[0];
            $timestamp        = $parts[1];

            // Skip invalid timestamps
            if ($timestamp == 0 || ! strtotime($timestamp)) {
                continue;
            }

            $timestamp = Carbon::parse((string) $timestamp, $device->company->timezone)
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');

            // Deduplicate
            $existingRecord = DB::table('biometric_device_attendances')
                ->where('employee_id', $deviceEmployeeId)
                ->where('timestamp', $timestamp)
                ->where('device_serial_number', $device->serial_number)
                ->where('company_id', $device->company_id)
                ->first();

            if ($existingRecord) {
                continue;
            }

            $biometricEmployee = BiometricEmployee::where('biometric_employee_id', $deviceEmployeeId)
                ->where('company_id', $device->company_id)
                ->first();

            $timestampDate = date('Y-m-d', strtotime($timestamp));

            $lastRecord = DB::table('biometric_device_attendances')
                ->where('employee_id', $deviceEmployeeId)
                ->whereDate('timestamp', $timestampDate)
                ->orderBy('timestamp', 'desc')
                ->where('company_id', $device->company_id)
                ->first();

            // Toggle clock-in / clock-out based on last record
            $status = 0;

            if ($lastRecord && $lastRecord->status1 == 0) {
                $status = 1; // Clock out
            } elseif ($lastRecord && $lastRecord->status1 == 1) {
                $status = 0; // Clock in
            }

            DB::table('biometric_device_attendances')->insert([
                'device_name'          => $device->device_name,
                'device_serial_number' => $device->serial_number,
                'user_id'              => $biometricEmployee?->user_id,
                'company_id'           => $device->company_id,
                'table'                => $request->input('table') ?? ' ',
                'stamp'                => $request->input('Stamp') ?? ' ',
                'employee_id'          => $deviceEmployeeId,
                'timestamp'            => $timestamp,
                'status1'              => $status,
                'status2'              => self::validateAndFormatInteger($parts[3] ?? null) ?? -1,
                'status3'              => self::validateAndFormatInteger($parts[4] ?? null) ?? -1,
                'status4'              => self::validateAndFormatInteger($parts[5] ?? null) ?? -1,
                'status5'              => self::validateAndFormatInteger($parts[6] ?? null) ?? -1,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);

            if (! $biometricEmployee) {
                $employeeDetails = EmployeeDetails::where('employee_id', $deviceEmployeeId)
                    ->where('company_id', $device->company_id)
                    ->first();

                if ($employeeDetails) {
                    $biometricEmployee = BiometricEmployee::create([
                        'biometric_employee_id' => $deviceEmployeeId,
                        'company_id'            => $device->company_id,
                        'user_id'               => $employeeDetails->user_id,
                    ]);
                }
            }

            if (! $biometricEmployee?->user) {
                continue;
            }

            self::markAttendance(
                $biometricEmployee->user,
                $timestamp,
                $device->serial_number,
                // parts[3] is the ZKTeco verify type:
                // 0 = password, 1 = fingerprint, 15 = face, other = card/NFC
                self::resolveClockMethod($parts[3] ?? null),
            );
        }
    }

    /**
     * Bridge a biometric event into the core `attendances` table.
     *
     * Adds GPS coordinates, clock method, geofence result, booking_id and
     * device_id to the attendance row so payroll and scheduling integrations
     * have a single source of truth.
     *
     * @param  User        $user
     * @param  string      $timestamp    UTC datetime string (Y-m-d H:i:s)
     * @param  string|null $deviceId     Serial number of the biometric device
     * @param  string      $method       fingerprint | face | nfc | gps | pin | manual
     * @param  float|null  $lat          GPS latitude at clock-in
     * @param  float|null  $lng          GPS longitude at clock-in
     */
    private static function markAttendance(
        User $user,
        string $timestamp,
        ?string $deviceId = null,
        string $method = 'fingerprint',
        ?float $lat = null,
        ?float $lng = null,
    ): void {
        $clockIn     = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
        $carbonDate  = $clockIn->copy()->startOfDay();

        // ── Geofence validation ──────────────────────────────────────────────
        $geofencePassed = true;

        if ($lat !== null && $lng !== null) {
            /** @var \App\Models\Company $company */
            $company = $user->company;

            // Use company address as geofence centre if available
            $centreLat = (float) ($company->latitude ?? 0);
            $centreLng = (float) ($company->longitude ?? 0);

            if ($centreLat !== 0.0 || $centreLng !== 0.0) {
                $geofencePassed = app(GeofenceService::class)->passes(
                    (int) $company->id,
                    $lat,
                    $lng,
                    $centreLat,
                    $centreLng
                );
            }
        }

        // ── Auto-link active booking ─────────────────────────────────────────
        $bookingId = null;

        if (class_exists(\Modules\BookingModule\Entities\Booking::class)) {
            try {
                $booking = \Modules\BookingModule\Entities\Booking::where('provider_id', $user->id)
                    ->where('status', 'ongoing')
                    ->whereDate('booking_date', $carbonDate->toDateString())
                    ->first();

                $bookingId = $booking?->id;
            } catch (\Throwable) {
                // BookingModule table may not exist in all environments
            }
        }

        // ── Write to core attendances table ──────────────────────────────────
        $lastAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('clock_in_time', $carbonDate)
            ->orderBy('clock_in_time', 'desc')
            ->first();

        if (! $lastAttendance || $lastAttendance->clock_out_time !== null) {
            // Clock In
            /** @var Attendance $attendance */
            $attendance = $user->attendance()->create([
                'clock_in_time'       => $clockIn,
                'half_day'            => 'no',
                'clock_in_type'       => 'biometric',
                'work_from_type'      => 'office',
                'clock_in_ip'         => request()->ip(),
                'clock_in_lat'        => $lat,
                'clock_in_lng'        => $lng,
                'clock_in_method'     => $method,
                'geofence_passed'     => $geofencePassed,
                'booking_id'          => $bookingId,
                'biometric_device_id' => $deviceId,
            ]);

            event(new BiometricClockIn(
                attendance:      $attendance,
                user:            $user,
                method:          $method,
                geofencePassed:  $geofencePassed,
                lat:             $lat,
                lng:             $lng,
                bookingId:       $bookingId,
                deviceId:        $deviceId,
            ));
        } else {
            // Clock Out
            $lastAttendance->update([
                'clock_out_time'      => $clockIn,
                'clock_out_type'      => 'biometric',
                'work_from_type'      => 'office',
                'clock_out_ip'        => request()->ip(),
                'clock_out_lat'       => $lat,
                'clock_out_lng'       => $lng,
                'clock_out_method'    => $method,
                'biometric_device_id' => $deviceId,
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Map a ZKTeco verify-type integer to a human-readable clock method string.
     *
     * ZKTeco verify types:
     *  0  = password / PIN
     *  1  = fingerprint
     *  3  = card / NFC
     *  15 = face recognition
     */
    private static function resolveClockMethod(mixed $verifyType): string
    {
        return match ((int) ($verifyType ?? 1)) {
            0       => 'pin',
            1       => 'fingerprint',
            3       => 'nfc',
            15      => 'face',
            default => 'fingerprint',
        };
    }

    private static function validateAndFormatInteger(mixed $value): ?int
    {
        if (isset($value) && $value !== '') {
            return is_numeric($value) ? (int) $value : null;
        }

        return null;
    }
}
