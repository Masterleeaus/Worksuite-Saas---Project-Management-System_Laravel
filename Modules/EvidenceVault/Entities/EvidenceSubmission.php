<?php

namespace Modules\EvidenceVault\Entities;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Evidence submission linked to a job/task.
 *
 * Each submission holds one or more photos and an optional digital signature.
 * A job must have at least one approved submission (with ≥1 photo) before it
 * can be marked complete.
 */
class EvidenceSubmission extends BaseModel
{
    use HasCompany;

    protected $table = 'evidence_vault_submissions';

    protected $fillable = [
        'company_id',
        'job_id',
        'job_reference',
        'submitted_by',
        'signature_data',
        'client_signed',
        'notes',
    ];

    protected $casts = [
        'client_signed' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function photos(): HasMany
    {
        return $this->hasMany(EvidencePhoto::class, 'submission_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Determine whether this submission satisfies the completion requirements
     * defined in the module config.
     */
    public function isComplete(): bool
    {
        $hasPhoto = $this->photos()->exists();

        if (config('evidence_vault.require_photo_on_completion', true) && !$hasPhoto) {
            return false;
        }

        if (config('evidence_vault.require_signature_on_completion', false)) {
            $hasSig = !empty($this->signature_data);
            $hasLockedSitePhoto = $this->photos()->where('is_site_locked_photo', true)->exists();

            if (!$hasSig && !$hasLockedSitePhoto) {
                return false;
            }
        }

        return true;
    }
}
