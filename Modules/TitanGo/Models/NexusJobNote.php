<?php

namespace Modules\TitanGo\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class NexusJobNote extends Model
{
    use HasCompany;

    protected $table = 'nexus_job_notes';

    protected $fillable = [
        'company_id',
        'fsm_order_id',
        'worker_id',
        'body',
    ];

    protected $casts = [
        'company_id'   => 'integer',
        'fsm_order_id' => 'integer',
        'worker_id'    => 'integer',
    ];
}
