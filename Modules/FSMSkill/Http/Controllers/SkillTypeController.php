<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSkill\Models\FSMSkillType;

class SkillTypeController extends Controller
{
    public function index()
    {
        $types = FSMSkillType::withCount('skills')->orderBy('name')->paginate(50);
        return view('fsmskill::skill_types.index', compact('types'));
    }

    public function create()
    {
        return view('fsmskill::skill_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string|max:65535',
            'active'      => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);

        FSMSkillType::create($data);

        return redirect()->route('fsmskill.skill-types.index')
            ->with('success', 'Skill type created.');
    }

    public function edit(int $id)
    {
        $type = FSMSkillType::findOrFail($id);
        return view('fsmskill::skill_types.edit', compact('type'));
    }

    public function update(Request $request, int $id)
    {
        $type = FSMSkillType::findOrFail($id);
        $data = $request->validate([
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string|max:65535',
            'active'      => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active', true);

        $type->update($data);

        return redirect()->route('fsmskill.skill-types.index')
            ->with('success', 'Skill type updated.');
    }

    public function destroy(int $id)
    {
        FSMSkillType::findOrFail($id)->delete();
        return redirect()->route('fsmskill.skill-types.index')
            ->with('success', 'Skill type deleted.');
    }
}
