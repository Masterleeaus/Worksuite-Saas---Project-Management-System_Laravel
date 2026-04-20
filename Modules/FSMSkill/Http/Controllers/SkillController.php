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
        $skills = FSMSkill::where('company_id', $this->companyId())
            ->with('skillType')
            ->withCount('levels')
            ->orderBy('name')
            ->paginate(50);
        return view('fsmskill::skills.index', compact('skills'));
    }

    public function create()
    {
        $types = FSMSkillType::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get();
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
        $data['company_id'] = $this->companyId();

        FSMSkill::create($data);

        return redirect()->route('fsmskill.skills.index')
            ->with('success', 'Skill created.');
    }

    public function edit(int $id)
    {
        $skill = FSMSkill::where('company_id', $this->companyId())->findOrFail($id);
        $types = FSMSkillType::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get();
        return view('fsmskill::skills.edit', compact('skill', 'types'));
    }

    public function update(Request $request, int $id)
    {
        $skill = FSMSkill::where('company_id', $this->companyId())->findOrFail($id);
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
        FSMSkill::where('company_id', $this->companyId())->findOrFail($id)->delete();
        return redirect()->route('fsmskill.skills.index')
            ->with('success', 'Skill deleted.');
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
