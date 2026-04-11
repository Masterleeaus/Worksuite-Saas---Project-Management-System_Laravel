<?php

namespace Modules\StaffCompliance\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\StaffCompliance\Entities\WorkerComplianceDocument;

class ComplianceExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public WorkerComplianceDocument $document;
    public int $daysUntilExpiry;

    public function __construct(WorkerComplianceDocument $document, int $daysUntilExpiry)
    {
        $this->document        = $document;
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $docType = $this->document->documentType;
        $name    = $docType ? $docType->name : 'Compliance Document';
        $expiry  = $this->document->expiry_date?->format('d M Y');

        return (new MailMessage())
            ->subject("Action Required: {$name} expires in {$this->daysUntilExpiry} day(s)")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your **{$name}** is due to expire on **{$expiry}** ({$this->daysUntilExpiry} day(s) remaining).")
            ->line('Please renew this document and upload the updated copy as soon as possible to remain compliant.')
            ->action('View Compliance Documents', url('/account/compliance/my-documents'))
            ->line('If you have already renewed this document, please upload it in the system to update your status.');
    }

    public function toArray($notifiable): array
    {
        return [
            'document_id'       => $this->document->id,
            'document_type'     => $this->document->documentType?->name,
            'expiry_date'       => $this->document->expiry_date?->toDateString(),
            'days_until_expiry' => $this->daysUntilExpiry,
        ];
    }
}
