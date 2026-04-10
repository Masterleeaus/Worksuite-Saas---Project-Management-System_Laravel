<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSkill\Models\FSMSkill;
use Modules\FSMSkill\Models\FSMSkillLevel;

class SkillLevelController extends Controller
{
    public function index(int $skillId)
    {
        $skill  = FSMSkill::findOrFail($skillId);
        $levels = FSMSkillLevel::where('skill_id', $skillId)->orderBy('progress')->get();
        return view('fsmskill::skill_levels.index', compact('skill', 'levels'));
    }

    public function create(int $skillId)
    {
        $skill = FSMSkill::findOrFail($skillId);
        return view('fsmskill::skill_levels.create', compact('skill'));
    }

    public function store(Request $request, int $skillId)
    {
        FSMSkill::findOrFail($skillId); // ensure parent exists

        $data = $request->validate([
            'name'          => 'required|string|max:128',
            'progress'      => 'required|integer|min:0|max:100',
            'default_level' => 'nullable|boolean',
        ]);
        $data['skill_id']      = $skillId;
        $data['default_level'] = $request->boolean('default_level', false);

        // Only one default per skill
        if ($data['default_level']) {
            FSMSkillLevel::where('skill_id', $skillId)->update(['default_level' => false]);
        }

        FSMSkillLevel::create($data);

        return redirect()->route('fsmskill.skill-levels.index', $skillId)
            ->with('success', 'Level created.');
    }

    public function edit(int $skillId, int $levelId)
    {
        $skill = FSMSkill::findOrFail($skillId);
        $level = FSMSkillLevel::where('skill_id', $skillId)->findOrFail($levelId);
        return view('fsmskill::skill_levels.edit', compact('skill', 'level'));
    }

    public function update(Request $request, int $skillId, int $levelId)
    {
        FSMSkill::findOrFail($skillId);
        $level = FSMSkillLevel::where('skill_id', $skillId)->findOrFail($levelId);

        $data = $request->validate([
            'name'          => 'required|string|max:128',
            'progress'      => 'required|integer|min:0|max:100',
            'default_level' => 'nullable|boolean',
        ]);
        $data['default_level'] = $request->boolean('default_level', false);

        if ($data['default_level']) {
            FSMSkillLevel::where('skill_id', $skillId)->where('id', '!=', $levelId)->update(['default_level' => false]);
        }

        $level->update($data);

        return redirect()->route('fsmskill.skill-levels.index', $skillId)
            ->with('success', 'Level updated.');
    }

    public function destroy(int $skillId, int $levelId)
    {
        $level = FSMSkillLevel::where('skill_id', $skillId)->findOrFail($levelId);
        $level->delete();
        return redirect()->route('fsmskill.skill-levels.index', $skillId)
            ->with('success', 'Level deleted.');
    }
}
