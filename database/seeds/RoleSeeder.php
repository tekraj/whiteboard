<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name'=>'sad','display_name'=>'Super Admin','description'=>'Access to everything','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'ad','display_name'=>'Admin','description'=>'Lower access than super admin','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'coad','display_name'=>'Co Admin','description'=>'Can view and manage payments, student session','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')],
            ['name'=>'mntr','display_name'=>'Monitor','description'=>'Monitor','created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]
        ];

        foreach($roles as $role){
            $extRole = Role::where('name',$role['name'])->first();
            if(!$extRole){
                Role::create($role);
            }
        }
    }
}
