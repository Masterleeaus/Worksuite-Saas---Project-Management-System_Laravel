<?php

namespace Modules\Performance\Notifications;

use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Performance\Entities\PerformanceReview;

class ReviewPublishedNotification extends BaseNotification
{
    use Queueable;

    public function __construct(private readonly PerformanceReview $review)
    {
        $company = \App\Models\Company::find($review->company_id);
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

        $url = url('/account/performance-dashboard');

        $content = 'Your performance review for period <strong>' . e($this->review->review_period) . '</strong> has been published.'
            . '<br><br>Overall Score: <strong>' . number_format((float) $this->review->overall_score, 1) . '</strong>'
            . '<br>Outcome: <strong>' . e($this->review->outcome ?? 'N/A') . '</strong>'
            . '<br><br>Please log in to view and acknowledge your review.';

        $build
            ->subject('Performance Review Published: ' . $this->review->review_period)
            ->markdown('mail.email', [
                'url'            => $url,
                'content'        => $content,
                'actionText'     => 'View My Review',
                'themeColor'     => $this->company->header_color ?? '#4B68FF',
                'notifiableName' => $notifiable->name,
            ]);

        parent::resetLocale();

        return $build;
    }

    public function toArray($notifiable): array
    {
        return [
            'id'         => $this->review->id,
            'heading'    => 'Performance Review Published',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
