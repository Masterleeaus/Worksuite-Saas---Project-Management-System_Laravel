<?php

namespace Modules\FSMCRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMTemplate;

class FSMLead extends Model
{
    use SoftDeletes;

    protected $table = 'fsm_leads';

    const STAGE_NEW       = 'new';
    const STAGE_QUALIFIED = 'qualified';
    const STAGE_WON       = 'won';
    const STAGE_LOST      = 'lost';

    protected $fillable = [
        'company_id',
        'name',
        'contact_name',
        'email',
        'phone',
        'partner_id',
        'notes',
        'stage',
        'expected_revenue',
        'close_date',
        'fsm_location_id',
        'service_type_id',
        'site_count',
        'estimated_hours',
        'create_recurring',
    ];

    protected $casts = [
        'company_id'        => 'integer',
        'partner_id'        => 'integer',
        'fsm_location_id'   => 'integer',
        'service_type_id'   => 'integer',
        'site_count'        => 'integer',
        'expected_revenue'  => 'decimal:2',
        'estimated_hours'   => 'decimal:2',
        'create_recurring'  => 'boolean',
        'close_date'        => 'date',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function fsmLocation()
    {
        return $this->belongsTo(FSMLocation::class, 'fsm_location_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(FSMTemplate::class, 'service_type_id');
    }

    public function orders()
    {
        return $this->hasMany(FSMOrder::class, 'lead_id');
    }

    // ── Computed ─────────────────────────────────────────────────────────────

    public function getFsmOrderCountAttribute(): int
    {
        return $this->orders()->count();
    }

    public function isWon(): bool
    {
        return $this->stage === self::STAGE_WON;
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public static function stages(): array
    {
        return [
            self::STAGE_NEW       => 'New',
            self::STAGE_QUALIFIED => 'Qualified',
            self::STAGE_WON       => 'Won',
            self::STAGE_LOST      => 'Lost',
        ];
    }

    public static function stageColors(): array
    {
        return [
            self::STAGE_NEW       => 'secondary',
            self::STAGE_QUALIFIED => 'info',
            self::STAGE_WON       => 'success',
            self::STAGE_LOST      => 'danger',
        ];
    }
}
