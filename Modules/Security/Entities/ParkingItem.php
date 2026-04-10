<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;

class ParkingItem extends BaseModel
{
    protected $table = 'tenan_parkir_items';
    protected $guarded = ['id'];

    public function parking()
    {
        return $this->belongsTo(Parking::class, 'parking_id');
    }
}
