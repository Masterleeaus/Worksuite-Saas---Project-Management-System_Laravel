<?php

namespace Modules\ReviewModule\Services;

use Illuminate\Support\Facades\Log;
use Modules\ReviewModule\Entities\Review;

/**
 * Handles creation of tokenised review records and dispatching
 * review-request notifications (email + SMS) to customers.
 *
 * All inter-module calls are guarded with class_exists / Schema::hasTable
 * so the service works even when dependent modules are absent.
 */
class ReviewRequestService
{
    /**
     * Create a pending review record (with token) for a completed booking
     * and dispatch the review-request notification.
     *
     * @param  \Modules\BookingModule\Entities\Booking  $booking
     * @return \Modules\ReviewModule\Entities\Review|null
     */
    public function createForBooking($booking): ?Review
    {
        try {
            // Load the first service from the booking
            $serviceId = null;
            if (method_exists($booking, 'details') && $booking->details->isNotEmpty()) {
                $serviceId = $booking->details->first()->service_id ?? null;
            }

            // Avoid duplicate requests for the same booking
            $existing = Review::where('booking_id', $booking->id)
                ->whereNotNull('review_token')
                ->first();

            if ($existing) {
                return $existing;
            }

            $token = bin2hex(random_bytes(24)); // 48-char cryptographically secure hex token

            $review = new Review();
            $review->booking_id       = $booking->id;
            $review->customer_id      = $booking->customer_id;
            $review->provider_id      = $booking->provider_id;
            $review->service_id       = $serviceId;
            $review->booking_date     = $booking->service_schedule ?? $booking->created_at;
            $review->review_token     = $token;
            $review->moderation_status = 'pending';
            $review->request_sent_at  = now();
            $review->is_active        = 0; // not yet submitted
            if (!empty($booking->company_id)) {
                $review->company_id = $booking->company_id;
            }
            $review->save();

            // Dispatch notifications
            $customer = $booking->customer ?? null;
            if ($customer) {
                $reviewUrl = route('reviews.public_form', $token);
                $this->sendSmsNotification($customer, $reviewUrl);
                $this->sendEmailNotification($customer, $booking, $reviewUrl);
            }

            return $review;
        } catch (\Throwable $e) {
            Log::warning('ReviewRequestService: failed to create review for booking ' . ($booking->id ?? '?'), [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Send SMS/WhatsApp review request notification.
     */
    private function sendSmsNotification($customer, string $reviewUrl): void
    {
        if (!class_exists(\Modules\Sms\Services\CleaningNotificationService::class)) {
            return;
        }

        try {
            $slug    = \Modules\Sms\Enums\SmsNotificationSlug::ReviewRequest;
            $message = \Modules\Sms\Services\CleaningNotificationService::resolveTemplate($slug, $customer->company_id ?? null, [
                'name' => $customer->f_name ?? $customer->name ?? 'Customer',
                'link' => $reviewUrl,
            ]);

            $service = app(\Modules\Sms\Services\CleaningNotificationService::class);
            $service->send($customer, $slug, $message);
        } catch (\Throwable $e) {
            Log::warning('ReviewRequestService: SMS failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send email review request notification.
     */
    private function sendEmailNotification($customer, $booking, string $reviewUrl): void
    {
        try {
            if (empty($customer->email)) {
                return;
            }

            \Illuminate\Support\Facades\Mail::send(
                'reviewmodule::emails.review_request',
                [
                    'customer'   => $customer,
                    'booking'    => $booking,
                    'reviewUrl'  => $reviewUrl,
                ],
                function ($mail) use ($customer) {
                    $mail->to($customer->email, $customer->f_name ?? $customer->name ?? 'Customer')
                         ->subject('How was your recent clean? Leave a review');
                }
            );
        } catch (\Throwable $e) {
            Log::warning('ReviewRequestService: email failed', ['error' => $e->getMessage()]);
        }
    }
}
