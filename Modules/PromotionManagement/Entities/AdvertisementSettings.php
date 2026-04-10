<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PromotionManagement\Traits\CompanyScoped;

class AdvertisementSettings extends Model
{
    use CompanyScoped;
    use HasFactory, HasUuid;

    protected $fillable = [
        'advertisement_id',
        'key',
        'value',
    ];

}