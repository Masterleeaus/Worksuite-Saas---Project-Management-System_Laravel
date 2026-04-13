<?php

namespace Modules\FSMCore\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class FSMStage extends Model
{
    use HasCompany;

    protected $table = 'fsm_stages';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'sequence',
        'is_completion_stage',
        'color',
    ];

    protected $casts = [
        'is_completion_stage' => 'boolean',
        'sequence' => 'integer',
        'company_id' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'stage_id');
    }
}
