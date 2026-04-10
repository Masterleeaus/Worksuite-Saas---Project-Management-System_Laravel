<?php

namespace Modules\CustomerConnect\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class CleaningOpsTest extends Command
{
    protected $signature = 'customerconnect:cleaning:status
        {status : Status (on_the_way|completed|...)}
        {--company_id=1 : company_id}
        {--user_id=1 : user_id}
        {--phone= : customer phone (any format)}
        {--contact_id= : existing customerconnect_contacts id}
        {--channel=sms : sms|whatsapp|email|telegram}
        {--client_name= : optional name}
        {--review_link= : optional review URL}
        {--before_after_link= : optional before/after URL}
        {--eta_window= : optional ETA window}';

    protected $description = 'Fire a cleaning status-change event to test Titan Connect cleaning automations.';

    public function handle(): int
    {
        $payload = [
            'event' => 'status_changed',
            'status' => (string)$this->argument('status'),
            'company_id' => (int)$this->option('company_id'),
            'user_id' => (int)$this->option('user_id'),
            'phone' => (string)($this->option('phone') ?? ''),
            'contact_id' => (int)($this->option('contact_id') ?? 0),
            'channel' => (string)$this->option('channel'),
            'client_name' => (string)($this->option('client_name') ?? ''),
            'review_link' => (string)($this->option('review_link') ?? ''),
            'before_after_link' => (string)($this->option('before_after_link') ?? ''),
            'eta_window' => (string)($this->option('eta_window') ?? ''),
        ];

        Event::dispatch('cleaning.job.status_changed', $payload);

        $this->info('Dispatched cleaning.job.status_changed with payload:');
        $this->line(json_encode($payload, JSON_PRETTY_PRINT));
        return Command::SUCCESS;
    }
}
