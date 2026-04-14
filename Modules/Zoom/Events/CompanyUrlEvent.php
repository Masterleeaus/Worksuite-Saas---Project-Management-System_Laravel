<?php

namespace Modules\Zoom\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyUrlEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly int $companyId = 0) {}
}
