<?php

namespace Modules\ManagedPremises\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\ManagedPremises\Entities\Concerns\BelongsToCompany;

class Property extends BaseModel
{
    use BelongsToCompany;

    protected $table = 'pm_properties';

    protected $guarded = ['id'];

    protected $casts = [
        'preferred_window_start'    => 'datetime',
        'preferred_window_end'      => 'datetime',
        'special_equipment_needed'  => 'boolean',
    ];

    /** Valid property types for cleaning/tradie businesses. */
    public const PROPERTY_TYPES = [
        'house'           => 'House',
        'apartment'       => 'Apartment',
        'office'          => 'Office',
        'warehouse'       => 'Warehouse',
        'strata_complex'  => 'Strata Complex',
        'airbnb'          => 'Airbnb',
    ];

    /** Cleaning frequency options. */
    public const CLEANING_FREQUENCIES = [
        'weekly'       => 'Weekly',
        'fortnightly'  => 'Fortnightly',
        'monthly'      => 'Monthly',
        'one-off'      => 'One-Off',
        'custom'       => 'Custom',
    ];

    /** Key holding status options. */
    public const KEY_HOLDING_STATUSES = [
        'office_holds'       => 'Office Holds Key',
        'customer_provides'  => 'Customer Provides',
        'lockbox'            => 'Lockbox',
        'other'              => 'Other',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(PropertyUnit::class, 'property_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(PropertyContact::class, 'property_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(PropertyJob::class, 'property_id');
    }

    public function meterReadings(): HasMany
    {
        return $this->hasMany(PropertyMeterReading::class, 'property_id')->orderByDesc('reading_date');
    }

    public function accessDetails(): HasOne
    {
        return $this->hasOne(PremiseAccess::class, 'premise_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PropertyPhoto::class, 'property_id');
    }

}
