<?php

namespace Modules\FSMSize\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\FSMCore\Entities\FsmLocation;

class FsmLocationSize extends Model
{
    protected $table = 'fsm_location_sizes';

    protected $fillable = ['location_id', 'size_id', 'quantity'];

    protected $casts = ['quantity' => 'decimal:2'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(FsmLocation::class, 'location_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(FsmSize::class, 'size_id');
    }
}
