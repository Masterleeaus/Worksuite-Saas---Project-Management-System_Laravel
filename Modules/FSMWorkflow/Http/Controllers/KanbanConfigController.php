<?php

namespace Modules\FSMWorkflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMTeam;
use Modules\FSMWorkflow\Models\FSMKanbanConfig;

class KanbanConfigController extends Controller
{
    private array $booleanFields = [
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

    /**
     * List all kanban configs (global + per-team).
     */
    public function index()
    {
        $configs = FSMKanbanConfig::with('team')->orderByRaw('team_id IS NOT NULL')->orderBy('id')->get();
        $teams   = FSMTeam::where('active', true)->orderBy('name')->get();

        return view('fsmworkflow::kanban_config.index', compact('configs', 'teams'));
    }

    /**
     * Edit (or create) the config for a given team (null = global).
     */
    public function edit(?int $teamId = null)
    {
        $config = FSMKanbanConfig::forTeam($teamId);
        $teams  = FSMTeam::where('active', true)->orderBy('name')->get();

        return view('fsmworkflow::kanban_config.edit', compact('config', 'teamId', 'teams'));
    }

    public function update(Request $request, ?int $teamId = null)
    {
        $validated = [];
        foreach ($this->booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        FSMKanbanConfig::updateOrCreate(
            ['team_id' => $teamId ?: null, 'company_id' => null],
            $validated
        );

        return redirect()->route('fsmworkflow.kanban_config.index')
            ->with('success', 'Kanban configuration saved.');
    }

    public function destroy(int $id)
    {
        FSMKanbanConfig::findOrFail($id)->delete();

        return redirect()->route('fsmworkflow.kanban_config.index')
            ->with('success', 'Kanban configuration deleted.');
    }
}
