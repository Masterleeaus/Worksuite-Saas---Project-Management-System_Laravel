<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LinkedInDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@fixitpro.com'],
            [
                'name' => 'Suleman Sikandar',
                'password' => Hash::make('password'),
                'active_status' => 1,
                'designation' => 'Super Administrator'
            ]
        );

        $role = \App\Models\Acl\Role::where('role_name', 'Super Admin')->first();
        if ($role) {
            \App\Models\Acl\AdminUserRole::updateOrCreate(
                ['admin_ID' => $admin->id, 'role_ID' => $role->ID]
            );
        }
    }
}
