<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\BookingModule\Traits\CompanyScoped;

class BookingPage extends Model
{
    use HasFactory, CompanyScoped;

    protected $table = 'booking_pages';

    protected $fillable = [
        'company_id',
        'created_by',
        'title',
        'slug',
        'status',
        'template',
        'headline',
        'subheadline',
        'hero_badge',
        'primary_button_label',
        'primary_button_url',
        'secondary_button_label',
        'secondary_button_url',
        'service_lines',
        'trust_lines',
        'faq_lines',
        'meta_title',
        'meta_description',
        'theme',
        'settings',
        'published_at',
    ];

    protected $casts = [
        'theme' => 'array',
        'settings' => 'array',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
