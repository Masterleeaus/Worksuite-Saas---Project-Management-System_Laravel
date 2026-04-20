<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMTemplate;
use Modules\FSMSkill\Models\FSMTemplateSkillRequirement;
use Modules\FSMSkill\Models\FSMSkill;

class TemplateSkillController extends Controller
{
    public function index(int $templateId)
    {
        $template     = FSMTemplate::where('company_id', $this->companyId())->findOrFail($templateId);
        $requirements = FSMTemplateSkillRequirement::with(['skill.skillType', 'skillLevel'])
            ->where('fsm_template_id', $template->id)
            ->get();
        $skills = FSMSkill::where('company_id', $this->companyId())->where('active', true)->with(['skillType', 'levels'])->orderBy('name')->get();

        return view('fsmskill::template_skills.index', compact('template', 'requirements', 'skills'));
    }

    public function store(Request $request, int $templateId)
    {
        $template = FSMTemplate::where('company_id', $this->companyId())->findOrFail($templateId);

        $data = $request->validate([
            'skill_id'       => 'required|integer|exists:fsm_skills,id',
            'skill_level_id' => 'nullable|integer|exists:fsm_skill_levels,id',
        ]);

        FSMTemplateSkillRequirement::updateOrCreate(
            ['fsm_template_id' => $template->id, 'skill_id' => $data['skill_id']],
            ['skill_level_id' => $data['skill_level_id'] ?? null]
        );

        return redirect()->route('fsmskill.template-skills.index', $templateId)
            ->with('success', 'Skill requirement added.');
    }

    public function destroy(int $templateId, int $id)
    {
        $template = FSMTemplate::where('company_id', $this->companyId())->findOrFail($templateId);
        $req = FSMTemplateSkillRequirement::where('fsm_template_id', $template->id)->findOrFail($id);
        $req->delete();

        return redirect()->route('fsmskill.template-skills.index', $templateId)
            ->with('success', 'Skill requirement removed.');
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
