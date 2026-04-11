<?php

namespace Modules\Inventory\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Local fallback supplier model used when the Suppliers module is not active.
 * When Suppliers module IS active, PurchaseOrder->supplier() resolves to
 * Modules\Suppliers\Entities\Supplier instead.
 */
class Supplier extends Model
{
    use SoftDeletes, HasCompany;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'abn',
        'notes',
    ];
}
