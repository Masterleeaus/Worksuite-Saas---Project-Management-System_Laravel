<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReachContactList extends Model
{
    use SoftDeletes;

    protected $table = 'reach_contact_lists';

    protected $fillable = [
        'company_id', 'name', 'description', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function contacts()
    {
        return $this->belongsToMany(ReachContact::class, 'reach_contact_list_contact', 'contact_list_id', 'contact_id');
    }
}
