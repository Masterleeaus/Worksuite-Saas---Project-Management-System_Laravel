<?php

namespace Modules\PaymentModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PaymentModule\Traits\HasUuid;
use Modules\PaymentModule\Traits\CompanyScoped;

class Setting extends Model
{
    use CompanyScoped;
    use HasFactory;
    use HasUuid;

    protected $table = 'addon_settings';

    protected $casts = [
        'live_values' => 'array',
        'test_values' => 'array',
        'is_active' => 'integer',
    ];

    protected $fillable = ['key_name', 'live_values', 'test_values', 'settings_type', 'mode', 'is_active', 'additional_data'];
}