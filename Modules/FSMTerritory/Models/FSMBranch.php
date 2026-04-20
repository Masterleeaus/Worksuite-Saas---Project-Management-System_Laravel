<?php

namespace Modules\FSMTerritory\Models;

use Illuminate\Database\Eloquent\Model;

class FSMBranch extends Model
{
    protected $table = 'fsm_branches';

    protected $fillable = ['company_id', 'name', 'description', 'district_id', 'manager_id'];

    protected $casts = ['company_id' => 'integer', 'district_id' => 'integer', 'manager_id' => 'integer'];

    public function district()
    {
        return $this->belongsTo(FSMDistrict::class, 'district_id');
    }

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function territories()
    {
        return $this->hasMany(FSMTerritory::class, 'branch_id');
    }
}
