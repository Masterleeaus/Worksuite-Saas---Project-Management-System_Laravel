<?php

namespace Modules\CustomerConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PortalPreference — stores a customer's self-service portal preferences.
 *
 * @property int         $id
 * @property int         $user_id
 * @property int|null    $preferred_cleaner_id
 * @property bool        $notify_email
 * @property bool        $notify_sms
 * @property string|null $special_instructions
 */
class PortalPreference extends Model
{
    protected $table = 'customerconnect_portal_preferences';

    protected $fillable = [
        'user_id',
        'preferred_cleaner_id',
        'notify_email',
        'notify_sms',
        'special_instructions',
    ];

    protected $casts = [
        'notify_email' => 'boolean',
        'notify_sms'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function preferredCleaner(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'preferred_cleaner_id');
    }
}
