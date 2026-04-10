<?php

namespace Modules\Aitools\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Aitools\Console\Commands\AitoolsGeneratePulse;

class GeneratePulseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ?int $company_id = null,
        public ?int $user_id = null,
        public string $window = 'daily',
        public int $hours = 24,
        public ?string $date = null
    ) {}

    public function handle(): void
    {
        // Reuse the command logic for consistency (best-effort).
        /** @var AitoolsGeneratePulse $cmd */
        $cmd = app(AitoolsGeneratePulse::class);
        $cmd->setLaravel(app());
        $cmd->run(new \Symfony\Component\Console\Input\ArrayInput([
            '--company_id' => $this->company_id,
            '--user_id' => $this->user_id,
            '--window' => $this->window,
            '--hours' => $this->hours,
            '--date' => $this->date,
        ]), new \Symfony\Component\Console\Output\NullOutput());
    }
}
