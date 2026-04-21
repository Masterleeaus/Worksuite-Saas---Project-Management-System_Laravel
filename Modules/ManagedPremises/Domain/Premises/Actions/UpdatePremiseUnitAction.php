<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\PropertyUnit;

class UpdatePremiseUnitAction
{
    public function handle(PropertyUnit $unit, array $data): PropertyUnit
    {
        $unit->fill($data);
        $unit->save();

        return $unit;
    }
}
