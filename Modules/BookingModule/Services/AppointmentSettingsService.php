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

        return $this->decode($row->value);
    }

    /**
     * Read a setting for an explicit company_id, bypassing global scope.
     * Falls back to the global (null company_id) default if no company-specific row exists.
     */
    public function getForCompany(string $key, int $companyId, $default = null)
    {
        // 1. Try company-specific row
        $row = AppointmentSetting::withoutGlobalScopes()
            ->where('key', $key)
            ->where('company_id', $companyId)
            ->first();

        if ($row) {
            return $this->decode($row->value);
        }

        // 2. Fall back to global (null company_id) default row
        $row = AppointmentSetting::withoutGlobalScopes()
            ->where('key', $key)
            ->whereNull('company_id')
            ->first();

        if ($row) {
            return $this->decode($row->value);
        }

        return $default;
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

    private function decode($value)
    {
        if (is_string($value)) {
            $json = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
        }
        return $value;
    }
}
