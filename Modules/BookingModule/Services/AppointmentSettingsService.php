<?php

namespace Modules\BookingModule\Services;

use Modules\BookingModule\Entities\AppointmentSetting;

class AppointmentSettingsService
{
    public function get(string $key, $default = null)
    {
        $workspace = function_exists('getActiveWorkSpace') ? getActiveWorkSpace() : null;
        $row = AppointmentSetting::query()
            ->when($workspace, fn($q) => $q->where('workspace', $workspace))
            ->where('key', $key)
            ->first();

        if (!$row) {
            return $default;
        }

        $value = $row->value;
        // Try to decode JSON values first.
        if (is_string($value)) {
            $json = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
        }
        return $value;
    }

    public function set(string $key, $value): void
    {
        $workspace = function_exists('getActiveWorkSpace') ? getActiveWorkSpace() : null;
        $createdBy = function_exists('creatorId') ? creatorId() : null;

        $storeValue = is_array($value) ? json_encode($value) : (string)$value;

        AppointmentSetting::updateOrCreate(
            ['workspace' => $workspace, 'key' => $key],
            ['value' => $storeValue, 'created_by' => $createdBy]
        );
    }
}
