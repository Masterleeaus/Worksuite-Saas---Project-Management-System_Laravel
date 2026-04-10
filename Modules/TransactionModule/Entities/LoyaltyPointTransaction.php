<?php

namespace Modules\TransactionModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserManagement\Entities\User;
use Modules\TransactionModule\Traits\CompanyScoped;

class LoyaltyPointTransaction extends Model
{
    use CompanyScoped;
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id', 'debit',  'credit',  'balance',  'reference',  'transaction_type'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\TransactionModule\Database\factories\LoyaltyPointTransactionFactory::new();
    }
}