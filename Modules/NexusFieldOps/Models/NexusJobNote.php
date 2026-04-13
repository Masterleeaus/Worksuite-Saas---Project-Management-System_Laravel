<?php

namespace Modules\NexusFieldOps\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMOrder;

class NexusJobNote extends Model
{
    protected $table = 'nexus_job_notes';

    protected $fillable = [
        'fsm_order_id',
        'user_id',
        'body',
    ];

    protected $casts = [
        'fsm_order_id' => 'integer',
        'user_id'      => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
