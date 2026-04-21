<?php

namespace Modules\QualityControl\Domain\Quality\Actions;

use Modules\QualityControl\Entities\QcRecord;

final class CreateComplaintFromQcFailureAction
{
    public function handle(QcRecord $record): ?int
    {
        if (!class_exists('Modules\\Complaint\\Entities\\Complaint')) {
            return null;
        }

        if (!empty($record->complaint_id)) {
            return (int) $record->complaint_id;
        }

        $complaintClass = 'Modules\\Complaint\\Entities\\Complaint';
        $complaint = new $complaintClass();
        $complaint->subject = 'QC Failure Follow-up';
        $complaint->status = 'open';
        $complaint->priority = 'high';
        $complaint->user_id = $record->cleaner_id ?: (function_exists('user') ? optional(user())->id : null);
        $complaint->quality_control_id = $record->schedule_id;
        $complaint->quality_control_reason = 'Auto-created from failed QC record #' . $record->id;
        $complaint->job_id = $record->booking_id;

        if (\Illuminate\Support\Facades\Schema::hasColumn('complaint', 'qc_record_id')) {
            $complaint->qc_record_id = $record->id;
        }

        $complaint->save();

        $record->complaint_id = $complaint->id;
        $record->save();

        return (int) $complaint->id;
    }
}
