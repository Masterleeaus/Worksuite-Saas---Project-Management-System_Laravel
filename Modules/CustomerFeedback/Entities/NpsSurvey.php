<?php

namespace Modules\CustomerFeedback\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * NpsSurvey — one row per survey instance sent to a client after a booking.
 *
 * @property int         $id
 * @property int         $client_id
 * @property int|null    $booking_id
 * @property int|null    $nps_score          0–10
 * @property int|null    $service_rating     1–5
 * @property int|null    $cleaner_rating     1–5
 * @property int|null    $punctuality_rating 1–5
 * @property string|null $comments
 * @property string      $survey_token       UUID used in public link
 * @property bool        $is_public          show as testimonial
 * @property \Carbon\Carbon|null $sent_at
 * @property \Carbon\Carbon|null $completed_at
 */
class NpsSurvey extends Model
{
    protected $table = 'nps_surveys';

    protected $fillable = [
        'client_id',
        'booking_id',
        'nps_score',
        'service_rating',
        'cleaner_rating',
        'punctuality_rating',
        'comments',
        'survey_token',
        'is_public',
        'sent_at',
        'completed_at',
    ];

    protected $casts = [
        'is_public'    => 'boolean',
        'sent_at'      => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $appends = ['nps_category'];

    /** Automatically generate a UUID survey_token on creation. */
    protected static function booted(): void
    {
        static::creating(function (self $survey) {
            if (empty($survey->survey_token)) {
                $survey->survey_token = (string) Str::uuid();
            }

            if (empty($survey->sent_at)) {
                $survey->sent_at = now();
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** True if the survey link has expired (default 7 days, configurable). */
    public function isExpired(): bool
    {
        $days = config('customer-feedback.survey_expiry_days', 7);

        return $this->sent_at !== null
            && $this->sent_at->addDays($days)->isPast();
    }

    /** True if the client has already completed this survey. */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /** NPS category: promoter (9–10), passive (7–8), detractor (0–6). */
    public function getNpsCategoryAttribute(): ?string
    {
        if ($this->nps_score === null) {
            return null;
        }

        if ($this->nps_score >= 9) {
            return 'promoter';
        }

        if ($this->nps_score >= 7) {
            return 'passive';
        }

        return 'detractor';
    }
}
