<?php

namespace Modules\TitanReach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReachContact extends Model
{
    use SoftDeletes;

    protected $table = 'reach_contacts';

    protected $fillable = [
        'company_id', 'user_id', 'name', 'phone', 'email',
        'telegram_chat_id', 'whatsapp_number', 'tags', 'meta', 'opted_out',
    ];

    protected $casts = [
        'tags'      => 'array',
        'meta'      => 'array',
        'opted_out' => 'boolean',
    ];

    public function lists()
    {
        return $this->belongsToMany(ReachContactList::class, 'reach_contact_list_contact', 'contact_id', 'contact_list_id');
    }

    public function conversations()
    {
        return $this->hasMany(ReachConversation::class, 'contact_id');
    }
}
