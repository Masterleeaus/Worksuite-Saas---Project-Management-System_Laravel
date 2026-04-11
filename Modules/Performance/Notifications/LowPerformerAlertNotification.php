<?php

namespace Modules\Performance\Notifications;

use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class LowPerformerAlertNotification extends BaseNotification
{
    use Queueable;

    public function __construct(
        private readonly float $score,
        private readonly int $companyId
    ) {
        $company = \App\Models\Company::find($companyId);
        if ($company) {
            $this->company = $company;
        }
    }

    public function via($notifiable): array
    {
        $via = ['database'];

        if ($notifiable->email_notifications && $notifiable->email !== '') {
            $via[] = 'mail';
        }

        return $via;
    }

    public function toMail($notifiable): MailMessage
    {
        $build = parent::build($notifiable);
        $threshold = (float) config('performance.low_performer_threshold', 40);

        $content = 'Your current performance score is <strong>' . number_format($this->score, 1) . '</strong>, '
            . 'which is below the minimum threshold of <strong>' . number_format($threshold, 1) . '</strong>.'
            . '<br><br>Please review your KPI dashboard and speak with your manager.';

        $build
            ->subject('Performance Alert: Score Below Threshold')
            ->markdown('mail.email', [
                'url'            => url('/account/performance-dashboard'),
                'content'        => $content,
                'actionText'     => 'View KPI Dashboard',
                'themeColor'     => $this->company->header_color ?? '#FF4B4B',
                'notifiableName' => $notifiable->name,
            ]);

        parent::resetLocale();

        return $build;
    }

    public function toArray($notifiable): array
    {
        return [
            'heading'    => 'Performance Alert: Score Below Threshold',
            'score'      => $this->score,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
