<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $user=User::create([
        "name"=>"admin",
        "email"=>"admin@admin.com",
        "password"=>"$2y$10$9glfsTsG/gAjbB/YbarEHOkwJ1biI9jGAZ5U5Yf35Wxti2fK5b3vG",
        "email_verified_at"=>now(),
        'app_user_type'=>1


      ]);
      $user->assignRole('admin');
        
        
        
    }
}
