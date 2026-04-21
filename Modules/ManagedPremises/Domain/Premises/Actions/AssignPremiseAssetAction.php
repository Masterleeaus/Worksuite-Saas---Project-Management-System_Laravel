<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyAsset;

class AssignPremiseAssetAction
{
    public function handle(Property $property, array $data): PropertyAsset
    {
        $data['property_id'] = $property->id;

        return PropertyAsset::create($data);
    }
}
