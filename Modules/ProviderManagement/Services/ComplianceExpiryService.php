<?php

namespace Modules\ProviderManagement\Services;

use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ComplianceExpiryService
{
    /**
     * Days before expiry to alert.
     */
    const ALERT_DAYS = 30;

    /**
     * Return employees whose compliance documents are expiring within ALERT_DAYS days.
     */
    public function getExpiringSoon(?int $companyId = null): Collection
    {
        $cutoff = Carbon::today()->addDays(self::ALERT_DAYS)->toDateString();
        $today  = Carbon::today()->toDateString();

        $query = EmployeeDetails::with('user')
            ->where(function ($q) use ($today, $cutoff) {
                $q->whereBetween('police_check_expiry', [$today, $cutoff])
                  ->orWhereBetween('insurance_expiry',  [$today, $cutoff])
                  ->orWhereBetween('wwcc_expiry',       [$today, $cutoff]);
            });

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->get()->map(function (EmployeeDetails $detail) use ($today, $cutoff) {
            $expiries = [];

            foreach (['police_check_expiry' => 'Police Check', 'insurance_expiry' => 'Insurance', 'wwcc_expiry' => 'WWCC'] as $column => $label) {
                $val = $detail->$column;
                if ($val && $val >= $today && $val <= $cutoff) {
                    $expiries[$label] = $val;
                }
            }

            return [
                'employee_id' => $detail->user_id,
                'employee'    => optional($detail->user)->name,
                'expiries'    => $expiries,
            ];
        })->filter(fn($e) => !empty($e['expiries']))->values();
    }

    /**
     * Return employees whose compliance documents are already expired.
     */
    public function getExpired(?int $companyId = null): Collection
    {
        $today = Carbon::today()->toDateString();

        $query = EmployeeDetails::with('user')
            ->where(function ($q) use ($today) {
                $q->where('police_check_expiry', '<', $today)
                  ->orWhere('insurance_expiry',  '<', $today)
                  ->orWhere('wwcc_expiry',       '<', $today);
            });

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->get();
    }
}
