<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\BookingModule\Traits\CompanyScoped;

class BookingRepeatHistory extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\BookingRepeatHistoryFactory::new();
    }

    public function repeat(): BelongsTo
    {
        return $this->belongsTo(BookingRepeat::class, 'booking_repeat_id');
    }
}