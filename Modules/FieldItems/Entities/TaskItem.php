<?php

namespace Modules\FieldItems\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskItem extends BaseModel
{
    use HasCompany;

    protected $table = 'task_items';

    protected $guarded = ['id'];

    protected $fillable = [
        'task_id',
        'item_id',
        'quantity',
        'unit_price',
        'company_id',
    ];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Task::class, 'task_id');
    }
}
