<?php

namespace Modules\Accountings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accountings\Actions\PostVisitCostAction;

class RecalculateVisitCostJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly array $payload)
    {
    }

    public function handle(PostVisitCostAction $postVisitCostAction): void
    {
        $postVisitCostAction->execute($this->payload);
    }
}
