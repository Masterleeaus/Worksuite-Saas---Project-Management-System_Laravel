<?php

namespace Modules\BookingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Traits\CompanyScoped;

class BookingPartialPayment extends Model
{
    use CompanyScoped;
    use HasFactory, HasUuid;

    protected $fillable = [
        'booking_id',
        'paid_with',
        'paid_amount',
        'due_amount',
    ];

    protected static function newFactory()
    {
        return \Modules\BookingModule\Database\factories\BookingPartialPaymentFactory::new();
    }
}