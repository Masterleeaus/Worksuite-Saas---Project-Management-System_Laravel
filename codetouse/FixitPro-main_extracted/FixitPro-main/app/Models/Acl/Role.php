<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbl_roles';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'role_name',
        'active_status',
        'display_order',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'tbl_role_privileges', 'role_ID', 'module_ID');
    }
}
