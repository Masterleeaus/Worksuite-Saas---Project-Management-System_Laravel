<?php

namespace Modules\PaymentModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PaymentModule\Traits\HasUuid;
use Modules\PaymentModule\Traits\CompanyScoped;

class PaymentRequest extends Model
{
    use CompanyScoped;
    use HasUuid;
    use HasFactory;

    protected $table = 'payment_requests';
}