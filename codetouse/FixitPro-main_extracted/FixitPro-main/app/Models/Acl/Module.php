<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'tbl_modules';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'module_name',
        'slug',
        'route',
        'category_id',
        'show_in_menu',
        'active_status',
        'display_order',
    ];

    public function category()
    {
        return $this->belongsTo(ModuleCategory::class, 'category_id', 'ID');
    }
}
