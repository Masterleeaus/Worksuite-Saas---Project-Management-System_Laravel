<?php

namespace Modules\QualityControl\Domain\Quality\Actions;

use Modules\QualityControl\Entities\QcRecord;
use Modules\QualityControl\Events\QcFailedEvent;
use Modules\QualityControl\Events\QcSeverityExceededEvent;

final class ScoreQcRecordAction
{
    public function handle(QcRecord $record): QcRecord
    {
        $record->recalculateScore();

        $fresh = $record->fresh();

        if ($fresh->status === 'fail') {
            QcFailedEvent::dispatch($fresh, 'score_action');

            $threshold = $this->severityThresholdIndex();
            $recordIndex = $this->severityIndex($fresh->severity_level ?? 'low');

            if ($recordIndex >= $threshold) {
                QcSeverityExceededEvent::dispatch($fresh, 'score_action');
            }
        }

        return $fresh;
    }

    private function severityIndex(?string $level): int
    {
        return array_search($level ?? 'low', ['low', 'medium', 'high', 'critical'], true) ?: 0;
    }

    private function severityThresholdIndex(): int
    {
        $threshold = config('quality_control.qc_auto_create_complaint_threshold', 'high');
        return array_search($threshold, ['low', 'medium', 'high', 'critical'], true) ?: 2;
    }
}
