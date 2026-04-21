<?php

namespace Modules\Accountings\Actions;

use Modules\Accountings\Entities\VisitCost;
use Modules\Accountings\Services\VisitCostService;

class PostVisitCostAction
{
    public function __construct(private readonly VisitCostService $visitCostService)
    {
    }

    public function execute(array $payload): VisitCost
    {
        return $this->visitCostService->calculateAndStore($payload);
    }
}
