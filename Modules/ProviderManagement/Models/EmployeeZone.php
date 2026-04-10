<?php

namespace Modules\ProviderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EmployeeZone extends Model
{
    protected $table = 'employee_zones';

    protected $fillable = ['employee_id', 'zone_id'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function zone()
    {
        if (class_exists(\Modules\ZoneManagement\Entities\Zone::class)) {
            return $this->belongsTo(\Modules\ZoneManagement\Entities\Zone::class, 'zone_id');
        }
        return null;
    }
}
