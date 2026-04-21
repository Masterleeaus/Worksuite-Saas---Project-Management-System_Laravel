<?php

namespace Modules\ManagedPremises\Domain\Premises\Actions;

use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyDocument;

class UploadPremiseDocumentAction
{
    public function handle(Property $property, array $data): PropertyDocument
    {
        $data['property_id'] = $property->id;

        return PropertyDocument::create($data);
    }
}
