<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;

class CardItems extends BaseModel
{
    protected $table = 'tr_access_card_items';
    protected $guarded = ['id'];

    public function card()
    {
        return $this->belongsTo(AccessCard::class, 'card_id');
    }
}
