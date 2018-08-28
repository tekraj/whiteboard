<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = ['name' => 'admin', 'email' => 'admin@gmail.com', 'password' => bcrypt('test@123'),'status'=>1];
        if (Admin::where('email', $admin['email'])->count() == 0) {
            Admin::create($admin);
        }


    }
}
