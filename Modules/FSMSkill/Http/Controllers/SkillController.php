<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSkill\Models\FSMSkill;
use Modules\FSMSkill\Models\FSMSkillType;

class SkillController extends Controller
{
    public function index()
    {
        $skills = FSMSkill::with('skillType')->withCount('levels')->orderBy('name')->paginate(50);
        return view('fsmskill::skills.index', compact('skills'));
    }

    public function create()
    {
        $types = FSMSkillType::where('active', true)->orderBy('name')->get();
        return view('fsmskill::skills.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'skill_type_id' => 'nullable|integer|exists:fsm_skill_types,id',
            'name'          => 'required|string|max:128',
            'description'   => 'nullable|string|max:65535',
            'active'        => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);

        FSMSkill::create($data);

        return redirect()->route('fsmskill.skills.index')
            ->with('success', 'Skill created.');
    }

    public function edit(int $id)
    {
        $skill = FSMSkill::findOrFail($id);
        $types = FSMSkillType::where('active', true)->orderBy('name')->get();
        return view('fsmskill::skills.edit', compact('skill', 'types'));
    }

    public function update(Request $request, int $id)
    {
        $skill = FSMSkill::findOrFail($id);
        $data  = $request->validate([
            'skill_type_id' => 'nullable|integer|exists:fsm_skill_types,id',
            'name'          => 'required|string|max:128',
            'description'   => 'nullable|string|max:65535',
            'active'        => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);

        $skill->update($data);

        return redirect()->route('fsmskill.skills.index')
            ->with('success', 'Skill updated.');
    }

    public function destroy(int $id)
    {
        FSMSkill::findOrFail($id)->delete();
        return redirect()->route('fsmskill.skills.index')
            ->with('success', 'Skill deleted.');
    }
}
