<?php

namespace Modules\Payroll\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PublicHoliday extends BaseModel
{
    use HasCompany;

    protected $guarded = ['id'];

    protected $dates = ['holiday_date'];

    protected $casts = [
        'holiday_date' => 'date',
        'is_national' => 'boolean',
        'is_manual' => 'boolean',
    ];

    /**
     * Return all holiday dates applicable for the given state in a date range.
     */
    public static function getDatesForState(?string $state, Carbon $from, Carbon $to): array
    {
        $companyId = company() ? company()->id : null;

        return self::where('company_id', $companyId)
            ->whereBetween('holiday_date', [$from->toDateString(), $to->toDateString()])
            ->where(function ($q) use ($state) {
                $q->where('is_national', true);
                if ($state) {
                    $q->orWhere('state', $state);
                }
            })
            ->pluck('holiday_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Check if a given date is a public holiday for the state.
     */
    public static function isHoliday(Carbon $date, ?string $state): bool
    {
        $companyId = company() ? company()->id : null;

        return self::where('company_id', $companyId)
            ->where('holiday_date', $date->toDateString())
            ->where(function ($q) use ($state) {
                $q->where('is_national', true);
                if ($state) {
                    $q->orWhere('state', $state);
                }
            })
            ->exists();
    }
}
