<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyServiceWindow;

class CreatePremiseServiceWindowAction
{
    public function handle(Property $property, array $data): PropertyServiceWindow
    {
        $data['property_id'] = $property->id;

        return PropertyServiceWindow::create($data);
    }
}
