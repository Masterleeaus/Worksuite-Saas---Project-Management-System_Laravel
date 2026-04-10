<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // Permissions
        $permTable = 'permissions';
        if (DB::getSchemaBuilder()->hasTable($permTable)) {
            $perms = [
                'inventory.view','inventory.manage',
                'inventory.suppliers.view','inventory.suppliers.manage',
                'inventory.purchasing.view','inventory.purchasing.manage',
                'inventory.transfer.view','inventory.transfer.manage',
            ];
            foreach ($perms as $p) {
                $exists = DB::table($permTable)->where('name',$p)->exists();
                if (!$exists) {
                    DB::table($permTable)->insert([
                        'name'=>$p,'guard_name'=>'web','created_at'=>now(),'updated_at'=>now()
                    ]);
                }
            }
        }

        // Add to packages (include in Business/Pro/Enterprise)
        if (DB::getSchemaBuilder()->hasTable('packages')) {
            $packages = DB::table('packages')->select('id','name','module_in_package')->get();
            foreach ($packages as $pkg) {
                $mods = json_decode($pkg->module_in_package ?? '[]', true) ?: [];
                if (!in_array('inventory', $mods) && in_array($pkg->name, ['Professional','Enterprise','Pro','Business'])) {
                    $mods[] = 'inventory';
                    DB::table('packages')->where('id',$pkg->id)
                      ->update(['module_in_package'=>json_encode(array_values(array_unique($mods)))]);
                }
            }
        }
    }
}
