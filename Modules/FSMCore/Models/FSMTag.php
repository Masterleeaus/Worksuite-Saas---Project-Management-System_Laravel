<?php

namespace Modules\FSMCore\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class FSMTag extends Model
{
    use HasCompany;

    protected $table = 'fsm_tags';

    protected $fillable = [
        'company_id',
        'name',
        'color',
    ];

    protected $casts = [
        'company_id' => 'integer',
    ];

    public function orders()
    {
        return $this->belongsToMany(FSMOrder::class, 'fsm_order_tag', 'fsm_tag_id', 'fsm_order_id');
    }
}
