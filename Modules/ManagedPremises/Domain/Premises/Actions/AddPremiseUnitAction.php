<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyUnit;

class AddPremiseUnitAction
{
    public function handle(Property $property, array $data): PropertyUnit
    {
        $data['property_id'] = $property->id;

        return PropertyUnit::create($data);
    }
}
