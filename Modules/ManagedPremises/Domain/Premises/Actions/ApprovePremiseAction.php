<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyApproval;

class ApprovePremiseAction
{
    public function handle(Property $property, array $data): PropertyApproval
    {
        $data['property_id'] = $property->id;

        return PropertyApproval::create($data);
    }
}
