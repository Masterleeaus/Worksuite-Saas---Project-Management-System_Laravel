<?php

namespace Modules\FSMServiceAgreement\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMLocation;

class FSMAgreementLine extends Model
{
    protected $table = 'fsm_agreement_lines';

    protected $fillable = [
        'agreement_id',
        'location_id',
        'service_description',
        'frequency',
        'unit_price',
        'sort_order',
    ];

    protected $casts = [
        'agreement_id' => 'integer',
        'location_id'  => 'integer',
        'unit_price'   => 'decimal:2',
        'sort_order'   => 'integer',
    ];

    public function agreement()
    {
        return $this->belongsTo(FSMServiceAgreement::class, 'agreement_id');
    }

    public function location()
    {
        return $this->belongsTo(FSMLocation::class, 'location_id');
    }
}
