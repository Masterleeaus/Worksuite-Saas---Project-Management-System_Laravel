<?php

namespace Modules\FSMTerritory\Models;

use Illuminate\Database\Eloquent\Model;

class FSMRegion extends Model
{
    protected $table = 'fsm_regions';

    protected $fillable = ['company_id', 'name', 'description', 'manager_id'];

    protected $casts = ['company_id' => 'integer', 'manager_id' => 'integer'];

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function districts()
    {
        return $this->hasMany(FSMDistrict::class, 'region_id');
    }
}
