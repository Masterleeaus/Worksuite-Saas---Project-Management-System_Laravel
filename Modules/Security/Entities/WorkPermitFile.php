<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;

class WorkPermitFile extends BaseModel
{
    protected $table = 'tr_workpermits_files';
    protected $guarded = ['id'];

    public function workPermit()
    {
        return $this->belongsTo(WorkPermit::class, 'wp_id');
    }
}
