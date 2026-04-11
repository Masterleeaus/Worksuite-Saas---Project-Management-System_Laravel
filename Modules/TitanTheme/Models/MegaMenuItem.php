<?php

namespace Modules\TitanTheme\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MegaMenuItem extends BaseModel
{
    protected $table = 'titan_mega_menu_items';

    protected $fillable = [
        'mega_menu_id',
        'parent_id',
        'label',
        'url',
        'route_name',
        'icon',
        'description',
        'thumbnail_path',
        'item_type',
        'open_in_new_tab',
        'is_active',
        'is_featured',
        'required_module',
        'sort_order',
        'column_span',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_active'       => 'boolean',
        'is_featured'     => 'boolean',
        'sort_order'      => 'integer',
        'column_span'     => 'integer',
    ];

    // Item type constants
    const TYPE_LINK     = 'link';
    const TYPE_GROUP    = 'group';
    const TYPE_FEATURED = 'featured';

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function megaMenu(): BelongsTo
    {
        return $this->belongsTo(MegaMenu::class, 'mega_menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MegaMenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MegaMenuItem::class, 'parent_id')
            ->orderBy('sort_order');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Resolve the final URL — prefers route_name over raw url.
     */
    public function resolvedUrl(): string
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Throwable $e) {
                // Route may not exist in current context; fall through.
            }
        }

        return $this->url ?? '#';
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
