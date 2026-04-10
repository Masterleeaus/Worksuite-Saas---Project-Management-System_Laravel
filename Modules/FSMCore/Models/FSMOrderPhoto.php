<?php

namespace Modules\FSMCore\Models;

use Illuminate\Database\Eloquent\Model;

class FSMOrderPhoto extends Model
{
    protected $table = 'fsm_order_photos';

    protected $fillable = [
        'fsm_order_id',
        'uploaded_by',
        'type',
        'path',
        'caption',
    ];

    protected $casts = [
        'fsm_order_id' => 'integer',
        'uploaded_by'  => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(FSMOrder::class, 'fsm_order_id');
    }

    public function worker()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }
}
