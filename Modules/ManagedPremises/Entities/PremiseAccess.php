<?php

namespace Modules\ManagedPremises\Entities;

use App\Models\BaseModel;
use Modules\ManagedPremises\Entities\Concerns\BelongsToCompany;

/**
 * Secure access details for a premise.
 * Stores codes (key, alarm, gate, intercom), parking, pet info and other
 * special notes that cleaners/tradies need before entering a property.
 */
class PremiseAccess extends BaseModel
{
    use BelongsToCompany;

    protected $table = 'premise_access';

    protected $guarded = ['id'];

    /**
     * The property (premise) this access record belongs to.
     */
    public function property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Property::class, 'premise_id');
    }
}
