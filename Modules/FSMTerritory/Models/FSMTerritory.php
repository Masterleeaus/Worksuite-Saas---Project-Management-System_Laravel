<?php

namespace Modules\FSMTerritory\Models;

use Illuminate\Database\Eloquent\Model;

class FSMTerritory extends Model
{
    protected $table = 'fsm_territories';

    protected $fillable = ['company_id', 'name', 'description', 'branch_id', 'type', 'zip_codes'];

    protected $casts = ['company_id' => 'integer', 'branch_id' => 'integer'];

    const TYPES = ['zip' => 'Zip Code', 'state' => 'State', 'country' => 'Country'];

    public function branch()
    {
        return $this->belongsTo(FSMBranch::class, 'branch_id');
    }

    public function district()
    {
        return $this->branch ? $this->branch->district : null;
    }

    public function region()
    {
        return $this->branch && $this->branch->district ? $this->branch->district->region : null;
    }
}
