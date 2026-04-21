<?php

namespace Modules\Accountings\Actions;

use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Events\JobCostFinalized;

class FinalizeJobCostAction
{
    public function execute(VisitCost $visitCost): VisitCost
    {
        $visitCost->status = 'finalized';
        $visitCost->finalized_at = now();
        $visitCost->save();

        event(new JobCostFinalized((int) $visitCost->company_id, ['visit_cost_id' => $visitCost->id, 'visit_ref' => $visitCost->visit_ref]));

        return $visitCost;
    }
}
