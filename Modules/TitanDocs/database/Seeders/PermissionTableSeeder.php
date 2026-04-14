<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Permission;
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
        Artisan::call('cache:clear');

        $Companypermission  = [
            'ai document manage',
            'ai document generate',
            'ai document generate PDF',
            'ai document generate Word',
            'ai document generate TXT',
            'ai document copy',
            'ai document save',
            'ai document create',
            'ai document view',
            'document history manage',
            'document history edit',
            'document history delete',
            'sidebar ai manage',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($Companypermission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','TitanDocs')->exists();
            if(!$table)
            {
                $permission =Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'TitanDocs',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
        // $this->call("OthersTableSeeder");
    }
}
