<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyKey;

class AddPremiseKeyAction
{
    public function handle(Property $property, array $data): PropertyKey
    {
        $data['property_id'] = $property->id;

        return PropertyKey::create($data);
    }
}
