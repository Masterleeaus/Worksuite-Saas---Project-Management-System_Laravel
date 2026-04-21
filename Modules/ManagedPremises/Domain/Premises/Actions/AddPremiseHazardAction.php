<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyHazard;

class AddPremiseHazardAction
{
    public function handle(Property $property, array $data): PropertyHazard
    {
        $data['property_id'] = $property->id;

        return PropertyHazard::create($data);
    }
}
