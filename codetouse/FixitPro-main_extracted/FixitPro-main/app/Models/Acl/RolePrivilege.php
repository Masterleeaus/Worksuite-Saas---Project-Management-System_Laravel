<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class RolePrivilege extends Model
{
    protected $table = 'tbl_role_privileges';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'role_ID',
        'module_ID',
    ];

    public static function hasPermission($role_ID, $currentUri)
    {
        return self::join('tbl_modules', 'tbl_role_privileges.module_ID', '=', 'tbl_modules.ID')
            ->where('role_ID', $role_ID)
            ->where(function($query) use ($currentUri) {
                $query->where('route', $currentUri)
                      ->orWhere('slug', $currentUri);
            })
            ->where('tbl_modules.active_status', 1)
            ->first();
    }
}
