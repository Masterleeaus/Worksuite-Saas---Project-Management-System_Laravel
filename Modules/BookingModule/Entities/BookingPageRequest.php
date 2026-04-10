<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\BookingModule\Traits\CompanyScoped;

class BookingPageRequest extends Model
{
    use HasFactory, CompanyScoped;

    protected $fillable = [
        'company_id',
        'created_by',
        'booking_page_id',
        'page_slug',
        'service_name',
        'customer_name',
        'email',
        'phone',
        'postcode',
        'preferred_date',
        'preferred_window',
        'notes',
        'status',
        'payload',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'payload' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(BookingPage::class, 'booking_page_id');
    }
}
