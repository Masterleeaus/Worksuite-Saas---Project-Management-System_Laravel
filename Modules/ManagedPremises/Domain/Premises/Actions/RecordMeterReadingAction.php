<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyMeterReading;

class RecordMeterReadingAction
{
    public function handle(Property $property, array $data): PropertyMeterReading
    {
        $data['property_id'] = $property->id;

        return PropertyMeterReading::create($data);
    }
}
