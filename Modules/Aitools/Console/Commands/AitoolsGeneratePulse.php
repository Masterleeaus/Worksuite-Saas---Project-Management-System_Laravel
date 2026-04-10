<?php

namespace Modules\Aitools\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Entities\AiToolsPulseSummary;
use Modules\Aitools\Services\Insights\PulseService;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class AitoolsGeneratePulse extends Command
{
    protected $signature = 'aitools:generate-pulse {--company_id=} {--user_id=} {--window=daily} {--hours=24} {--date=}';

    protected $description = 'Generate and store an AI Tools business pulse summary (best-effort).';

    public function handle(): int
    {
        $companyId = $this->option('company_id');
        $userId = $this->option('user_id');
        $window = (string) $this->option('window');
        $hours = (int) $this->option('hours');
        $forDate = $this->option('date') ? Carbon::parse($this->option('date'))->toDateString() : Carbon::now()->toDateString();

        $targets = [];

        if (!empty($companyId)) {
            $targets[] = ['company_id' => (int)$companyId, 'user_id' => $userId ? (int)$userId : null];
        } else {
            // Best-effort: derive company_ids from companies table
            if (Schema::hasTable('companies')) {
                $ids = DB::table('companies')->select('id')->limit(5000)->pluck('id')->toArray();
                foreach ($ids as $id) {
                    $targets[] = ['company_id' => (int)$id, 'user_id' => null];
                }
            } else {
                $targets[] = ['company_id' => null, 'user_id' => null];
            }
        }

        /** @var PulseService $pulse */
        $pulse = app(PulseService::class);

        foreach ($targets as $t) {
            $ctx = new AitoolsContext(
                company_id: $t['company_id'],
                user_id: $t['user_id'],
                timezone: config('app.timezone', 'UTC'),
                locale: config('app.locale', 'en'),
                role: null,
                permissions: []
            );

            $data = $pulse->getPulse($ctx, $hours);

            AiToolsPulseSummary::updateOrCreate(
                [
                    'company_id' => $t['company_id'],
                    'user_id' => $t['user_id'],
                    'for_date' => $forDate,
                    'window' => $window,
                ],
                [
                    'summary' => $this->renderSummaryText($data),
                    'metrics' => $data['aggregates'] ?? [],
                ]
            );

            $this->info('Generated pulse for company_id=' . ($t['company_id'] ?? 'null') . ' window=' . $window . ' date=' . $forDate);
        }

        return self::SUCCESS;
    }

    private function renderSummaryText(array $data): string
    {
        $signals = $data['signals'] ?? [];
        $agg = $data['aggregates'] ?? [];

        $lines = [];
        $lines[] = 'Business pulse (' . ($data['window_hours'] ?? '?') . 'h window):';
        if (!empty($agg)) {
            $lines[] = 'Key counts: ' . json_encode($agg);
        }
        if (!empty($signals)) {
            $lines[] = 'Recent signals (top ' . min(10, count($signals)) . '):';
            foreach (array_slice($signals, 0, 10) as $s) {
                $lines[] = '- [' . ($s['severity'] ?? 'info') . '] ' . ($s['type'] ?? 'signal') . ' @ ' . ($s['occurred_at'] ?? '');
            }
        } else {
            $lines[] = 'No recent signals captured yet.';
        }

        return implode("\n", $lines);
    }
}
