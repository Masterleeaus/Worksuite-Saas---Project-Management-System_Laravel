<?php

namespace Modules\TitanGo\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class TitanGoChecklistCompletion extends Model
{
    use HasCompany;

    protected $table = 'titan_go_checklist_completions';

    protected $fillable = [
        'company_id',
        'visit_id',
        'worker_id',
        'step_index',
        'instruction',
        'photo_data',
        'signature_data',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'visit_id'     => 'integer',
        'worker_id'    => 'integer',
        'step_index'   => 'integer',
        'completed_at' => 'datetime',
    ];

    /**
     * Do NOT return photo/signature data in default serialization to keep
     * list responses light; callers should explicitly select those columns.
     */
    protected $hidden = ['photo_data', 'signature_data'];
}
