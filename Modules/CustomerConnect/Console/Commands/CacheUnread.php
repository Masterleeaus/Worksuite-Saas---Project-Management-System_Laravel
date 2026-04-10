<?php

namespace Modules\CustomerConnect\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\CustomerConnect\Services\Inbox\UnreadCounter;

class CacheUnread extends Command
{
    protected $signature = 'customerconnect:cache-unread {--company_id=} {--user_id=}';
    protected $description = 'Warm CustomerConnect unread badge cache (best-effort).';

    public function handle(UnreadCounter $counter)
    {
        $companyId = $this->option('company_id');
        $userId = $this->option('user_id');

        if ($companyId && $userId) {
            $count = $counter->get((int)$userId, (int)$companyId);
            $this->info("Cached unread for company {$companyId}, user {$userId}: {$count}");
            return 0;
        }

        // Best-effort: iterate recent active companies and users (minimal)
        $companies = DB::table('companies')->select('id')->limit(50)->get();
        foreach ($companies as $c) {
            $users = DB::table('users')->where('company_id', $c->id)->select('id')->limit(50)->get();
            foreach ($users as $u) {
                $counter->get((int)$u->id, (int)$c->id);
            }
        }

        $this->info('Cached unread counts (best-effort).');
        return 0;
    }
}
