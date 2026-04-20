<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyContact;

class AddPremiseContactAction
{
    public function handle(Property $property, array $data): PropertyContact
    {
        $data['property_id'] = $property->id;

        return PropertyContact::create($data);
    }
}
