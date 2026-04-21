<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;

class ArchivePremiseAction
{
    public function handle(Property $property): void
    {
        if (in_array('status', $property->getFillable(), true) || isset($property->status)) {
            $property->status = 'archived';
            $property->save();
            return;
        }

        $property->delete();
    }
}
