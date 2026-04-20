<?php

namespace Modules\QualityControl\Domain\Quality\Actions;

use Modules\QualityControl\Entities\QcRecord;

final class ScoreQcRecordAction
{
    public function handle(QcRecord $record): QcRecord
    {
        $record->recalculateScore();

        return $record->fresh();
    }
}
