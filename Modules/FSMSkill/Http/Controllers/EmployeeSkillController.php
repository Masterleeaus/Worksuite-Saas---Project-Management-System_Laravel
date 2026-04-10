<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\FSMSkill\Models\FSMEmployeeSkill;
use Modules\FSMSkill\Models\FSMSkill;
use Modules\FSMSkill\Models\FSMSkillLevel;

class EmployeeSkillController extends Controller
{
    public function index(int $userId)
    {
        $user   = \App\Models\User::findOrFail($userId);
        $skills = FSMEmployeeSkill::with(['skill.skillType', 'skillLevel'])
            ->where('user_id', $userId)
            ->orderBy('skill_id')
            ->get();

        return view('fsmskill::employee_skills.index', compact('user', 'skills'));
    }

    public function create(int $userId)
    {
        $user   = \App\Models\User::findOrFail($userId);
        $skills = FSMSkill::where('active', true)->with('levels')->orderBy('name')->get();
        return view('fsmskill::employee_skills.create', compact('user', 'skills'));
    }

    public function store(Request $request, int $userId)
    {
        \App\Models\User::findOrFail($userId);

        $data = $request->validate([
            'skill_id'       => 'required|integer|exists:fsm_skills,id',
            'skill_level_id' => 'nullable|integer|exists:fsm_skill_levels,id',
            'expiry_date'    => 'nullable|date',
            'notes'          => 'nullable|string|max:65535',
            'certificate'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $certPath = null;
        if ($request->hasFile('certificate')) {
            $certPath = $request->file('certificate')
                ->store("fsm_skill_certificates/{$userId}", 'public');
        }

        FSMEmployeeSkill::updateOrCreate(
            ['user_id' => $userId, 'skill_id' => $data['skill_id']],
            [
                'skill_level_id'   => $data['skill_level_id'] ?? null,
                'expiry_date'      => $data['expiry_date'] ?? null,
                'certificate_path' => $certPath,
                'notes'            => $data['notes'] ?? null,
            ]
        );

        return redirect()->route('fsmskill.employee-skills.index', $userId)
            ->with('success', 'Skill saved.');
    }

    public function edit(int $userId, int $id)
    {
        $user       = \App\Models\User::findOrFail($userId);
        $empSkill   = FSMEmployeeSkill::where('user_id', $userId)->findOrFail($id);
        $skills     = FSMSkill::where('active', true)->with('levels')->orderBy('name')->get();
        $levels     = FSMSkillLevel::where('skill_id', $empSkill->skill_id)->orderBy('progress')->get();
        return view('fsmskill::employee_skills.edit', compact('user', 'empSkill', 'skills', 'levels'));
    }

    public function update(Request $request, int $userId, int $id)
    {
        $empSkill = FSMEmployeeSkill::where('user_id', $userId)->findOrFail($id);

        $data = $request->validate([
            'skill_level_id' => 'nullable|integer|exists:fsm_skill_levels,id',
            'expiry_date'    => 'nullable|date',
            'notes'          => 'nullable|string|max:65535',
            'certificate'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('certificate')) {
            // Remove old certificate if any
            if ($empSkill->certificate_path) {
                Storage::disk('public')->delete($empSkill->certificate_path);
            }
            $data['certificate_path'] = $request->file('certificate')
                ->store("fsm_skill_certificates/{$userId}", 'public');
        }

        $empSkill->update($data);

        return redirect()->route('fsmskill.employee-skills.index', $userId)
            ->with('success', 'Skill updated.');
    }

    public function destroy(int $userId, int $id)
    {
        $empSkill = FSMEmployeeSkill::where('user_id', $userId)->findOrFail($id);

        if ($empSkill->certificate_path) {
            Storage::disk('public')->delete($empSkill->certificate_path);
        }

        $empSkill->delete();

        return redirect()->route('fsmskill.employee-skills.index', $userId)
            ->with('success', 'Skill removed.');
    }

    /** AJAX: return skill levels for a given skill (used in dynamic form). */
    public function levels(int $skillId)
    {
        $levels = FSMSkillLevel::where('skill_id', $skillId)
            ->orderBy('progress')
            ->get(['id', 'name', 'progress']);
        return response()->json($levels);
    }
}
