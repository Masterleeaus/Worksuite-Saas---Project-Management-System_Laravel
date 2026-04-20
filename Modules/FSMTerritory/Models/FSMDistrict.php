<?php

namespace Modules\FSMTerritory\Models;

use Illuminate\Database\Eloquent\Model;

class FSMDistrict extends Model
{
    protected $table = 'fsm_districts';

    protected $fillable = ['company_id', 'name', 'description', 'region_id', 'manager_id'];

    protected $casts = ['company_id' => 'integer', 'region_id' => 'integer', 'manager_id' => 'integer'];

    public function region()
    {
        return $this->belongsTo(FSMRegion::class, 'region_id');
    }

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function branches()
    {
        return $this->hasMany(FSMBranch::class, 'district_id');
    }
}
