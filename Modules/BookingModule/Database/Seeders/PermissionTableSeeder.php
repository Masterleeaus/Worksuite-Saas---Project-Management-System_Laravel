<?php

namespace Modules\BookingModule\Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        Artisan::call('cache:clear');

        $permission = [
            'appointment manage',
            'appointment dashboard manage',
            'appointment dispatch',
            'appointment dispatch.edit',
            'appointment doctor',
            'appointments manage',
            'appointments create',
            'appointments edit',
            'appointments delete',
            'appointments show',
            'appointments copy link',
            'appointments assign',
            'appointments reassign',
            'appointment settings manage',
            'appointment view notifications',
            'appointment notifications manage',
            'question manage',
            'question create',
            'question edit',
            'question delete',
            'schedule manage',
            'schedule delete',
            'schedule show',
            'schedule action',
            'schedule assign',
            'schedule reassign',
            'schedule bulk assign',
            'schedule workload view',
            'schedule capacity manage',
        ];

        $company_role = Role::where('name', 'company')->first();
        foreach ($permission as $key => $value) {
            $table = Permission::where('name', $value)->where('module', 'Appointment')->exists();
            if (!$table) {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'Appointment',
                        'created_by' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );

                if (!$company_role->hasPermission($value)) {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
