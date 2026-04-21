<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\PropertyServicePlan;

class UpdatePremiseServicePlanAction
{
    public function handle(PropertyServicePlan $plan, array $data): PropertyServicePlan
    {
        $plan->fill($data);
        $plan->save();

        return $plan;
    }
}
