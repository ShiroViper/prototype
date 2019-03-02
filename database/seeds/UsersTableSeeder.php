<?php

use Illuminate\Database\Seeder;

use App\User;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'lname' => Str::random(10),
            'fname' => Str::random(10),
            'mname' => Str::random(10),
            'email' => Str::random(10).'@admin.com',
            'password' => bcrypt(123456),
            'user_type' => 2,
            'address' => Str::random(10),
            'remember_token'=> Str::random(10)
        ];

        User::create($admin);
    }
}
