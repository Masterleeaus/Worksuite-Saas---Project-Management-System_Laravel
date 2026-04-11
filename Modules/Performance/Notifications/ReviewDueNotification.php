<?php

namespace Modules\Performance\Notifications;

use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class ReviewDueNotification extends BaseNotification
{
    use Queueable;

    public function __construct(
        private readonly string $reviewPeriod,
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

        $build
            ->subject('Performance Review Due: ' . $this->reviewPeriod)
            ->markdown('mail.email', [
                'url'             => url('/account/performance-dashboard'),
                'content'         => 'Your performance review for period <strong>' . e($this->reviewPeriod) . '</strong> is due. Please complete it at your earliest convenience.',
                'actionText'      => 'View Performance Dashboard',
                'themeColor'      => $this->company->header_color ?? '#4B68FF',
                'notifiableName'  => $notifiable->name,
            ]);

        parent::resetLocale();

        return $build;
    }

    public function toArray($notifiable): array
    {
        return [
            'heading'    => 'Performance Review Due',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
