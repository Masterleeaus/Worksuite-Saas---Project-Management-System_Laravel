<?php

namespace Modules\Suppliers\Events;

use Illuminate\Queue\SerializesModels;

class SupplierLinked
{
    use SerializesModels;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
