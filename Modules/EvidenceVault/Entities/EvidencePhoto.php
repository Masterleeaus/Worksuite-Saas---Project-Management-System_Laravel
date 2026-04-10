<?php

namespace Modules\EvidenceVault\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A single photo attached to an EvidenceSubmission.
 */
class EvidencePhoto extends BaseModel
{
    protected $table = 'evidence_vault_photos';

    protected $fillable = [
        'submission_id',
        'original_filename',
        'disk_filename',
        'disk',
        'disk_path',
        'mime_type',
        'file_size',
        'is_site_locked_photo',
    ];

    protected $casts = [
        'is_site_locked_photo' => 'boolean',
        'file_size'            => 'integer',
    ];

    protected $appends = ['photo_url'];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function submission(): BelongsTo
    {
        return $this->belongsTo(EvidenceSubmission::class, 'submission_id');
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Return a publicly accessible URL for the stored photo.
     * Falls back gracefully when Storage::url() is unavailable.
     */
    public function getPhotoUrlAttribute(): string
    {
        try {
            return Storage::disk($this->disk)->url($this->disk_path . '/' . $this->disk_filename);
        } catch (\Exception $e) {
            return '';
        }
    }
}
