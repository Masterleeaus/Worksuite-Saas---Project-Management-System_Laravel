<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;

class UpdatePremiseAction
{
    public function handle(Property $property, array $data): Property
    {
        $property->fill($data);
        $property->save();

        return $property;
    }
}
