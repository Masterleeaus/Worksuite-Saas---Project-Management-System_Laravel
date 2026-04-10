<?php

namespace Modules\FSMWorkflow\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\FSMCore\Models\FSMTeam;

/**
 * Per-team (or global) configuration for Kanban card enrichment.
 * team_id = null means the global default configuration.
 */
class FSMKanbanConfig extends Model
{
    protected $table = 'fsm_kanban_configs';

    protected $fillable = [
        'company_id',
        'team_id',
        'show_skills',
        'show_stock_status',
        'show_vehicle',
        'show_timesheet_progress',
        'show_warning_overdue',
        'show_warning_gps',
        'show_warning_photo',
        'show_warning_cert',
        'show_client_rating',
        'show_size',
    ];

    protected $casts = [
        'company_id'              => 'integer',
        'team_id'                 => 'integer',
        'show_skills'             => 'boolean',
        'show_stock_status'       => 'boolean',
        'show_vehicle'            => 'boolean',
        'show_timesheet_progress' => 'boolean',
        'show_warning_overdue'    => 'boolean',
        'show_warning_gps'        => 'boolean',
        'show_warning_photo'      => 'boolean',
        'show_warning_cert'       => 'boolean',
        'show_client_rating'      => 'boolean',
        'show_size'               => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(FSMTeam::class, 'team_id');
    }

    /**
     * Retrieve the effective config for a given team (falls back to global default).
     */
    public static function forTeam(?int $teamId, ?int $companyId = null): self
    {
        // Try team-specific config first
        if ($teamId) {
            $config = static::where('team_id', $teamId)
                ->where(function ($q) use ($companyId) {
                    $q->where('company_id', $companyId)->orWhereNull('company_id');
                })
                ->first();

            if ($config) {
                return $config;
            }
        }

        // Fall back to global (team_id = null)
        $global = static::whereNull('team_id')
            ->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhereNull('company_id');
            })
            ->first();

        if ($global) {
            return $global;
        }

        // Return defaults without persisting
        return new static([
            'show_skills'             => true,
            'show_stock_status'       => true,
            'show_vehicle'            => true,
            'show_timesheet_progress' => true,
            'show_warning_overdue'    => true,
            'show_warning_gps'        => false,
            'show_warning_photo'      => false,
            'show_warning_cert'       => false,
            'show_client_rating'      => false,
            'show_size'               => true,
        ]);
    }
}
