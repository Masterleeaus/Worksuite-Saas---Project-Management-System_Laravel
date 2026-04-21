<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyVisit;

class CreatePremiseVisitAction
{
    public function handle(Property $property, array $data): PropertyVisit
    {
        $data['property_id'] = $property->id;

        return PropertyVisit::create($data);
    }
}
