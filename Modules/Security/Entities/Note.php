<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Modules\Units\Entities\Unit;

class Note extends BaseModel
{
    use HasCompany;

    protected $table = 'tr_notes';
    protected $guarded = ['id'];
    const MODULE_NAME = 'security_notes';

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
