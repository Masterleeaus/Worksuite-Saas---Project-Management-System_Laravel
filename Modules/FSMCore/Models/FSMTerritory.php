<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMTerritory extends Model
{
    protected $table = 'fsm_territories';

    protected $fillable = [
        'company_id',
        'parent_id',
        'name',
        'type',
        'zip_codes',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'company_id' => 'integer',
        'parent_id' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(FSMTerritory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FSMTerritory::class, 'parent_id');
    }

    public function locations()
    {
        return $this->hasMany(FSMLocation::class, 'territory_id');
    }
}
