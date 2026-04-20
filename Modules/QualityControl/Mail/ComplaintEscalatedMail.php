<?php

namespace Modules\QualityControl\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComplaintEscalatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly int $complaintId,
        public readonly ?string $reason = null,
        public readonly ?int $qcRecordId = null,
    ) {
    }

    public function build(): static
    {
        return $this
            ->subject('[QC Escalation] Complaint #' . $this->complaintId . ' requires attention')
            ->view('quality_control::mail.complaint_escalated');
    }
}
