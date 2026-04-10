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

    /**
     * Relationship to the Zone model — only active when ZoneManagement module is present.
     * Use hasZoneRelation() to check before eager-loading.
     */
    public function zone(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        $zoneClass = class_exists(\Modules\ZoneManagement\Entities\Zone::class)
            ? \Modules\ZoneManagement\Entities\Zone::class
            : self::class; // fallback to self keeps return type valid; zone_id won't match

        return $this->belongsTo($zoneClass, 'zone_id');
    }

    /**
     * Returns true when the ZoneManagement module is loaded and the zone() relation is meaningful.
     */
    public static function hasZoneRelation(): bool
    {
        return class_exists(\Modules\ZoneManagement\Entities\Zone::class);
    }
}
