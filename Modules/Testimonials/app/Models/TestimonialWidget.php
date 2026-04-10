<?php

namespace Modules\Testimonials\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Testimonials\Traits\CompanyScoped;

/**
 * Class TestimonialWidget
 *
 * @property int         $id
 * @property int|null    $company_id
 * @property string      $name
 * @property array|null  $settings_json
 * @property string|null $embed_code
 * @property bool        $is_active
 */
class TestimonialWidget extends Model
{
    use SoftDeletes;
    use CompanyScoped;

    protected $table = 'testimonial_widgets';

    protected $fillable = [
        'company_id', 'name', 'settings_json', 'embed_code', 'is_active',
    ];

    protected $casts = [
        'settings_json' => 'array',
        'is_active'     => 'boolean',
    ];

    /**
     * (Re)generate the embed code for this widget.
     */
    public function generateEmbedCode(): string
    {
        $url = route('testimonials.widget.embed', ['widget' => $this->id]);
        return '<iframe src="' . e($url) . '" width="100%" height="400" frameborder="0" loading="lazy"></iframe>';
    }
}
