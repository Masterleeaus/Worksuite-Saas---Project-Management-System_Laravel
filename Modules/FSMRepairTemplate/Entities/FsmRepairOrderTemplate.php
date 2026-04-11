<?php

namespace Modules\FSMRepairTemplate\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FsmRepairOrderTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'fsm_repair_order_templates';

    protected $fillable = ['company_id', 'name', 'instructions', 'type_id', 'active'];

    protected $casts = ['active' => 'boolean'];
}
