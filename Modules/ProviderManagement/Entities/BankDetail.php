<?php

namespace Modules\ProviderManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ProviderManagement\Traits\CompanyScoped;

class BankDetail extends Model
{
    use CompanyScoped;
    use HasFactory, HasUuid;

    protected $fillable = ['provider_id', 'bank_name', 'branch_name', 'acc_no', 'acc_holder_name', 'routing_number'];

}