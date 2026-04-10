<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PromotionManagement\Traits\CompanyScoped;

class PushNotificationUser extends Model
{
    use CompanyScoped;
    use HasFactory, HasUuid;

    protected $fillable = ['push_notification_id', 'user_id'];

    protected static function newFactory()
    {
        return \Modules\PromotionManagement\Database\factories\PushNotificationUserFactory::new();
    }
}