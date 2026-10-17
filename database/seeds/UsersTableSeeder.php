<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Hemant Upadhyay",
            'email' => 'hemant.adsl@gmail.com',
            'password' => bcrypt('pass'),
            'mobile'=>8890355118,
            'status'=>1,
            'email_verified_at'=>'2018-01-16 00:00:00',
            'company_id'=>0,
        ]);
        DB::table('role_user')->insert([
            'user_id' =>1,
            'role_id' =>1
        ]);
    }
}
