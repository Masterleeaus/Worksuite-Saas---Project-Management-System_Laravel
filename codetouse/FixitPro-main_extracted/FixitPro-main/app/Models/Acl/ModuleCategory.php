<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class ModuleCategory extends Model
{
    protected $table = 'tbl_module_categories';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'name',
        'active_status',
        'display_order',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class, 'category_id', 'ID');
    }
}
