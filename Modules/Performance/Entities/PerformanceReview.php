<?php

namespace Modules\Performance\Entities;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends BaseModel
{
    use HasCompany;

    protected $table = 'performance_reviews';

    protected $fillable = [
        'company_id',
        'employee_id',
        'reviewer_id',
        'review_period',
        'review_type',
        'kpi_scores',
        'strengths',
        'improvements',
        'goals',
        'outcome',
        'overall_score',
        'employee_acknowledged',
        'acknowledged_at',
        'added_by',
        'last_updated_by',
    ];

    protected $casts = [
        'kpi_scores'             => 'array',
        'employee_acknowledged'  => 'boolean',
        'acknowledged_at'        => 'datetime',
        'overall_score'          => 'decimal:2',
    ];

    const REVIEW_TYPES = ['monthly', 'quarterly', 'annual'];

    const OUTCOMES = ['meets', 'exceeds', 'below'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Mark review as acknowledged by employee (timestamped).
     */
    public function acknowledge(): void
    {
        $this->update([
            'employee_acknowledged' => true,
            'acknowledged_at'       => now(),
        ]);
    }

    /**
     * Determine outcome label from overall score.
     */
    public static function outcomeFromScore(float $score): string
    {
        if ($score >= 85) {
            return 'exceeds';
        }

        if ($score >= 60) {
            return 'meets';
        }

        return 'below';
    }
}
