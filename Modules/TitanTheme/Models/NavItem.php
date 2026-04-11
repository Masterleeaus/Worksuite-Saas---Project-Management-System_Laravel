<?php

namespace Modules\TitanTheme\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavItem extends BaseModel
{
    use HasCompany;

    protected $table = 'titan_nav_items';

    protected $fillable = [
        'company_id',
        'parent_id',
        'label',
        'url',
        'route_name',
        'icon',
        'panel',
        'item_type',
        'is_active',
        'open_in_new_tab',
        'visible_to_roles',
        'required_module',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'open_in_new_tab' => 'boolean',
        'sort_order'      => 'integer',
    ];

    // Panel constants
    const PANEL_SIDEBAR = 'sidebar';
    const PANEL_HEADER  = 'header';

    // Item type constants
    const TYPE_LINK      = 'link';
    const TYPE_SEPARATOR = 'separator';
    const TYPE_HEADING   = 'heading';

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavItem::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
                // Route may not exist; fall through.
            }
        }

        return $this->url ?? '#';
    }

    /**
     * Return list of roles that can see this item (null = all roles).
     */
    public function visibleRoles(): ?array
    {
        if (empty($this->visible_to_roles)) {
            return null;
        }

        return array_map('trim', explode(',', $this->visible_to_roles));
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForPanel($query, string $panel)
    {
        return $query->where('panel', $panel);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
