<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class AdminUserRole extends Model
{
    protected $table = 'tbl_admin_user_roles';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'admin_ID',
        'role_ID',
    ];
}
