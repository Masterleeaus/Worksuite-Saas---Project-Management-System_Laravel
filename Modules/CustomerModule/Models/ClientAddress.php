<?php

namespace Modules\CustomerModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * Multiple properties / addresses per core Worksuite client (User with role 'client').
 *
 * @property int         $id
 * @property int         $client_id
 * @property int|null    $company_id
 * @property string|null $label
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $suburb
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $property_type
 * @property string|null $special_instructions
 * @property string|null $pet_info
 * @property bool        $is_primary
 * @property bool        $key_holding
 * @property string|null $alarm_code
 * @property string|null $access_notes
 */
class ClientAddress extends Model
{
    use SoftDeletes;

    protected $table = 'client_addresses';

    protected $fillable = [
        'client_id',
        'company_id',
        'label',
        'address_line_1',
        'address_line_2',
        'suburb',
        'city',
        'state',
        'postal_code',
        'country',
        'property_type',
        'special_instructions',
        'pet_info',
        'is_primary',
        'key_holding',
        'alarm_code',
        'access_notes',
    ];

    protected $casts = [
        'is_primary'  => 'boolean',
        'key_holding' => 'boolean',
    ];

    /** The core Worksuite client (User with role 'client'). */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /** One-line representation of the address. */
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->suburb,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]));
    }
}
