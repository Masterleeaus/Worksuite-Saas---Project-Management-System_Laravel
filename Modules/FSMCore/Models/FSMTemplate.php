<?php

namespace Modules\FSMCore\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class FSMTemplate extends Model
{
    use HasCompany;

    protected $table = 'fsm_templates';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'checklist',
        'estimated_duration_minutes',
        'active',
    ];

    protected $casts = [
        'checklist' => 'array',
        'active' => 'boolean',
        'company_id' => 'integer',
        'estimated_duration_minutes' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'template_id');
    }
}
