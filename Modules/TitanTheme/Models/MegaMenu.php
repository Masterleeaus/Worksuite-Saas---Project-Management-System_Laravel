<?php

namespace Modules\TitanTheme\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MegaMenu extends BaseModel
{
    use HasCompany;

    protected $table = 'titan_mega_menus';

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'icon',
        'sort_order',
        'is_active',
        'required_module',
        'created_by',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function items(): HasMany
    {
        return $this->hasMany(MegaMenuItem::class, 'mega_menu_id')
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MegaMenuItem::class, 'mega_menu_id')
            ->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
