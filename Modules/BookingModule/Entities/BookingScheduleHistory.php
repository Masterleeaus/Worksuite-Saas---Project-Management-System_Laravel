<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserManagement\Entities\User;
use Modules\BookingModule\Traits\CompanyScoped;

class BookingScheduleHistory extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}