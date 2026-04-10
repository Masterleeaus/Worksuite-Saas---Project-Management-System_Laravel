<?php

namespace Modules\FSMSkill\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\FSMSkill\Models\FSMEmployeeSkill;
use Modules\FSMSkill\Models\FSMOrderSkillRequirement;
use Modules\FSMSkill\Models\FSMTemplateSkillRequirement;

class SkillMatchService
{
    /**
     * Check whether a worker satisfies all skill requirements for an FSM Order.
     *
     * Returns an array:
     *   'match'    => bool   – true = all requirements met
     *   'issues'   => array  – list of human-readable problems (empty when match=true)
     *   'warnings' => array  – non-blocking notices (e.g. expiring soon)
     */
    public function checkOrderMatch(int $userId, int $orderId): array
    {
        $requirements = FSMOrderSkillRequirement::with(['skill', 'skillLevel'])
            ->where('fsm_order_id', $orderId)
            ->get();

        return $this->evaluate($userId, $requirements);
    }

    /**
     * Check whether a worker satisfies all skill requirements for a Template.
     */
    public function checkTemplateMatch(int $userId, int $templateId): array
    {
        $requirements = FSMTemplateSkillRequirement::with(['skill', 'skillLevel'])
            ->where('fsm_template_id', $templateId)
            ->get();

        return $this->evaluate($userId, $requirements);
    }

    /**
     * Filter a collection of user IDs to those who meet all order requirements.
     *
     * @param  int[]  $userIds
     */
    public function filterQualifiedWorkers(array $userIds, int $orderId): array
    {
        return array_values(array_filter($userIds, function (int $uid) use ($orderId) {
            return $this->checkOrderMatch($uid, $orderId)['match'];
        }));
    }

    // -------------------------------------------------------------------------

    private function evaluate(int $userId, Collection $requirements): array
    {
        if ($requirements->isEmpty()) {
            return ['match' => true, 'issues' => [], 'warnings' => []];
        }

        $workerSkills = FSMEmployeeSkill::with('skillLevel')
            ->where('user_id', $userId)
            ->get()
            ->keyBy('skill_id');

        $issues   = [];
        $warnings = [];

        foreach ($requirements as $req) {
            $skillName = $req->skill->name ?? "(skill #{$req->skill_id})";
            $empSkill  = $workerSkills->get($req->skill_id);

            if ($empSkill === null) {
                $issues[] = "Missing required skill: {$skillName}";
                continue;
            }

            // Check expiry
            if ($empSkill->expiry_date !== null) {
                if ($empSkill->isExpired()) {
                    $issues[] = "Certification expired for: {$skillName} (expired {$empSkill->expiry_date->format('d M Y')})";
                    continue;
                }
                if ($empSkill->isExpiringSoon()) {
                    $warnings[] = "Certification for {$skillName} expires soon ({$empSkill->expiry_date->format('d M Y')})";
                }
            }

            // Check level if specified
            if ($req->skill_level_id !== null && $empSkill->skill_level_id !== null) {
                $reqLevel  = $req->skillLevel;
                $empLevel  = $empSkill->skillLevel;

                if ($reqLevel && $empLevel && $empLevel->progress < $reqLevel->progress) {
                    $issues[] = "Skill level insufficient for {$skillName}: has {$empLevel->name} (progress {$empLevel->progress}), requires {$reqLevel->name} (progress {$reqLevel->progress})";
                }
            } elseif ($req->skill_level_id !== null && $empSkill->skill_level_id === null) {
                $reqLevel = $req->skillLevel;
                $levelName = $reqLevel ? $reqLevel->name : "level #{$req->skill_level_id}";
                $warnings[] = "No level recorded for {$skillName}; required level is {$levelName}";
            }
        }

        return [
            'match'    => count($issues) === 0,
            'issues'   => $issues,
            'warnings' => $warnings,
        ];
    }
}
