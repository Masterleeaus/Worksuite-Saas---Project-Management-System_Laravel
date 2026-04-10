<?php

namespace Modules\CustomerFeedback\Services;

use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CustomerFeedback\Entities\FeedbackTicket;
use Modules\CustomerFeedback\Entities\NpsSurvey;

class NpsSurveyService
{
    /**
     * Dispatch a post-service survey for a completed booking.
     *
     * @param  int       $clientId
     * @param  int|null  $bookingId
     * @return NpsSurvey
     */
    public function dispatchSurvey(int $clientId, ?int $bookingId = null): NpsSurvey
    {
        $survey = NpsSurvey::create([
            'client_id'  => $clientId,
            'booking_id' => $bookingId,
            // survey_token and sent_at are filled by the model's booted() hook
        ]);

        // TODO: send email / SMS with survey link (integrate with Sms module)
        // Example: Mail::to($survey->client->email)->send(new NpsSurveyMail($survey));

        return $survey;
    }

    /**
     * Submit a survey response.  Enforces:
     *  - token validity
     *  - expiry (configurable, default 7 days)
     *  - single-submission (duplicate prevention)
     *  - rating bounds (nps_score 0-10, star ratings 1-5)
     *
     * @param  NpsSurvey $survey
     * @param  array     $data  Keys: nps_score, service_rating, cleaner_rating, punctuality_rating, comments
     * @return NpsSurvey
     *
     * @throws \RuntimeException
     */
    public function submitResponse(NpsSurvey $survey, array $data): NpsSurvey
    {
        if ($survey->isCompleted()) {
            throw new \RuntimeException('survey_already_completed');
        }

        if ($survey->isExpired()) {
            throw new \RuntimeException('survey_expired');
        }

        $npsScore     = isset($data['nps_score'])          ? (int) $data['nps_score']          : null;
        $serviceRating = isset($data['service_rating'])    ? (int) $data['service_rating']      : null;
        $cleanerRating = isset($data['cleaner_rating'])    ? (int) $data['cleaner_rating']      : null;
        $punctuality   = isset($data['punctuality_rating']) ? (int) $data['punctuality_rating'] : null;

        // Validate bounds
        if ($npsScore !== null && ($npsScore < 0 || $npsScore > 10)) {
            throw new \InvalidArgumentException('nps_score must be between 0 and 10');
        }

        foreach (['service_rating' => $serviceRating, 'cleaner_rating' => $cleanerRating, 'punctuality_rating' => $punctuality] as $field => $value) {
            if ($value !== null && ($value < 1 || $value > 5)) {
                throw new \InvalidArgumentException("{$field} must be between 1 and 5");
            }
        }

        DB::transaction(function () use ($survey, $npsScore, $serviceRating, $cleanerRating, $punctuality, $data) {
            $survey->update([
                'nps_score'          => $npsScore,
                'service_rating'     => $serviceRating,
                'cleaner_rating'     => $cleanerRating,
                'punctuality_rating' => $punctuality,
                'comments'           => $data['comments'] ?? null,
                'completed_at'       => now(),
            ]);

            // If cleaner rating is provided, update the employee's aggregate star rating
            if ($cleanerRating !== null && $survey->booking_id !== null) {
                $this->updateCleanerRating($survey->booking_id, $cleanerRating);
            }

            // Low NPS (0–6 detractor) → auto-create complaint ticket
            if ($npsScore !== null && $npsScore <= 6) {
                $this->createComplaintTicket($survey);
            }

            // High NPS (9–10 promoter) → invite Google review via Sms module
            if ($npsScore !== null && $npsScore >= 9) {
                $this->requestGoogleReview($survey);
            }
        });

        return $survey->fresh();
    }

    /**
     * Recalculate and persist a cleaner's aggregate star_rating on employee_details.
     * Division-by-zero safe: if no ratings exist, sets rating to 0.00.
     *
     * @param  int $bookingId   Used to resolve the assigned employee
     * @param  int $newRating   The newly submitted 1–5 cleaner rating
     */
    private function updateCleanerRating(int $bookingId, int $newRating): void
    {
        try {
            // Retrieve all submitted cleaner_rating values for this employee via all
            // completed surveys that share the same booking (proxy for same cleaner).
            // We recompute from the DB to keep the aggregate accurate.
            $employeeId = $this->resolveEmployeeFromBooking($bookingId);

            if ($employeeId === null) {
                return;
            }

            /** @var \App\Models\EmployeeDetails|null $details */
            $details = EmployeeDetails::where('user_id', $employeeId)->first();

            if (!$details) {
                return;
            }

            // Aggregate all cleaner_ratings for surveys associated with this employee's bookings
            $aggregate = NpsSurvey::whereNotNull('cleaner_rating')
                ->whereNotNull('completed_at')
                ->whereIn('booking_id', function ($q) use ($employeeId) {
                    // Sub-select booking IDs assigned to this employee.
                    // Adjust the table/column names to match the actual bookings table.
                    $q->select('id')
                      ->from('bookings')
                      ->where('assigned_user_id', $employeeId)
                      ->orWhere('serviceman_id', $employeeId);
                })
                ->selectRaw('AVG(cleaner_rating) as avg_rating, COUNT(*) as total')
                ->first();

            $avgRating   = $aggregate && $aggregate->total > 0
                ? round((float) $aggregate->avg_rating, 2)
                : 0.00;
            $totalRatings = $aggregate ? (int) $aggregate->total : 0;

            $details->update([
                'star_rating'   => $avgRating,
                'total_ratings' => $totalRatings,
            ]);
        } catch (\Throwable $e) {
            Log::warning('CustomerFeedback: failed to update cleaner star_rating', [
                'booking_id' => $bookingId,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Attempt to resolve an employee user_id from a booking record.
     * Returns null if BookingModule is not installed or booking not found.
     */
    private function resolveEmployeeFromBooking(int $bookingId): ?int
    {
        if (!DB::getSchemaBuilder()->hasTable('bookings')) {
            return null;
        }

        $booking = DB::table('bookings')->where('id', $bookingId)->first();

        if (!$booking) {
            return null;
        }

        // BookingModule uses 'assigned_user_id' or 'serviceman_id' depending on version
        return $booking->assigned_user_id ?? $booking->serviceman_id ?? null;
    }

    /**
     * Auto-create a complaint FeedbackTicket for low-NPS (detractor) responses.
     */
    private function createComplaintTicket(NpsSurvey $survey): void
    {
        try {
            FeedbackTicket::create([
                'company_id'    => optional(optional($survey->client)->company)->id ?? 0,
                'user_id'       => $survey->client_id,
                'title'         => 'Low NPS Survey — Score: ' . $survey->nps_score,
                'description'   => $survey->comments
                    ?? 'Automatically created from low NPS score (' . $survey->nps_score . '/10).',
                'feedback_type' => FeedbackTicket::TYPE_COMPLAINT,
                'status'        => FeedbackTicket::STATUS_OPEN,
                'priority'      => FeedbackTicket::PRIORITY_HIGH,
                'nps_score'     => $survey->nps_score,
            ]);
        } catch (\Throwable $e) {
            Log::warning('CustomerFeedback: failed to create complaint ticket for low NPS', [
                'survey_id' => $survey->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send a Google Review invitation for high-NPS (promoter) respondents.
     * Uses the Sms module if available.
     */
    private function requestGoogleReview(NpsSurvey $survey): void
    {
        try {
            if (!class_exists(\Modules\Sms\Services\CleaningNotificationService::class)) {
                return;
            }

            $client = $survey->client;

            if (!$client) {
                return;
            }

            /** @var \Modules\Sms\Services\CleaningNotificationService $sms */
            $sms = app(\Modules\Sms\Services\CleaningNotificationService::class);

            // Use a generic "thank you / review" message if the slug exists.
            // Falls back silently if the slug is not configured.
            $sms->sendBySlug('google-review-request', $client, [
                'client_name' => $client->name,
            ]);
        } catch (\Throwable $e) {
            Log::info('CustomerFeedback: Google review invitation skipped', [
                'survey_id' => $survey->id,
                'reason'    => $e->getMessage(),
            ]);
        }
    }
}
