<?php

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;
use App\Domain\Acl\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       // $this->call(PermissionSeeder::class);
        //$this->call(PageSeeder::class);
        //$this->call(CategorySeeder::class);
         $superAdmin = factory(Admin::class)->create([
            'email' => 'quocdatmkt@gmail.com',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'password' => bcrypt('D@t123123@'),
        ]);
       $superAdmin->assignRole([
            'name' => 'superadmin',
                 ]);       
    }
}
