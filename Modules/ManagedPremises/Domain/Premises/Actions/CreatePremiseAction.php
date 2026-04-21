<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;

class CreatePremiseAction
{
    public function handle(array $data): Property
    {
        return Property::create($data);
    }
}
