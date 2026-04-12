<?php

namespace Modules\TitanTheme\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemePreset extends BaseModel
{
    use HasCompany;

    protected $table = 'titan_theme_presets';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'heading_font',
        'body_font',
        'sidebar_width',
        'header_height',
        'border_radius',
        'custom_css',
        'extra_settings',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'extra_settings' => 'array',
        'is_active'      => 'boolean',
        'sidebar_width'  => 'integer',
        'header_height'  => 'integer',
        'border_radius'  => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build an associative array of CSS variable name => value for this preset.
     */
    public function toCssVariables(): array
    {
        $prefix = config('titantheme.css_var_prefix', '--tt-');

        return array_filter([
            $prefix . 'primary'    => $this->primary_color,
            $prefix . 'secondary'  => $this->secondary_color,
            $prefix . 'accent'     => $this->accent_color,
            $prefix . 'bg'         => $this->background_color,
            $prefix . 'text'       => $this->text_color,
            $prefix . 'font-head'  => $this->heading_font ? "'{$this->heading_font}', sans-serif" : null,
            $prefix . 'font-body'  => $this->body_font ? "'{$this->body_font}', sans-serif" : null,
            $prefix . 'sidebar-w'  => $this->sidebar_width  ? $this->sidebar_width  . 'px' : null,
            $prefix . 'header-h'   => $this->header_height  ? $this->header_height  . 'px' : null,
            $prefix . 'radius'     => $this->border_radius !== null ? $this->border_radius . 'px' : null,
        ]);
    }
}
