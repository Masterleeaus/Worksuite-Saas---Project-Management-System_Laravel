<?php

namespace Modules\Testimonials\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Testimonials\Traits\CompanyScoped;

/**
 * Class Testimonial
 *
 * @property int         $id
 * @property int|null    $company_id
 * @property string      $client_name
 * @property string|null $customer_name   Alias for client_name (cleaning-business label)
 * @property string|null $suburb          Local social proof
 * @property string|null $service_type    residential | commercial | end-of-lease …
 * @property string      $description
 * @property string|null $content         Alias for description
 * @property string|null $client_image
 * @property string|null $photo_path      Main / after photo
 * @property string|null $before_photo
 * @property string|null $after_photo
 * @property string|null $video_url       YouTube / Vimeo embed
 * @property int         $star_rating     1–5
 * @property string      $position
 * @property bool        $status
 * @property bool        $is_published
 * @property bool        $is_featured
 * @property string      $source          manual | review | feedback
 * @property int|null    $source_id
 * @property string|null $booking_id
 * @property int|null    $order_by
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Testimonial extends Model
{
    use SoftDeletes;
    use CompanyScoped;

    protected $table = 'testimonials';

    protected $fillable = [
        'company_id',
        // Legacy fields (kept for backward compat)
        'client_name', 'description', 'client_image', 'position', 'status', 'order_by',
        // Cleaning-business fields
        'customer_name', 'suburb', 'service_type', 'content',
        'star_rating', 'photo_path', 'before_photo', 'after_photo', 'video_url',
        'is_published', 'is_featured', 'source', 'source_id', 'booking_id',
        'deleted_at', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'status'       => 'boolean',
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'star_rating'  => 'integer',
    ];

    public $timestamps = true;

    // ------------------------------------------------------------------
    // Accessors / helpers
    // ------------------------------------------------------------------

    /**
     * Generate the storage URL for any file stored in testimonials/.
     */
    public function file(string $filename): string
    {
        return url('storage/testimonials') . '/' . $filename;
    }

    /**
     * Resolved display name — prefer new field, fall back to legacy.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->customer_name ?: ($this->client_name ?: '');
    }

    /**
     * Resolved body text — prefer new field, fall back to legacy.
     */
    public function getBodyAttribute(): string
    {
        return $this->content ?: ($this->description ?: '');
    }
}

