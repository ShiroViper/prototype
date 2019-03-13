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
        $users = [
            [
                'lname' => Str::random(10),
                'fname' => Str::random(10),
                'mname' => Str::random(10),
                'email' => Str::random(10).'@alkansya.com',
                'cell_num' => Str::random(10),
                'password' => bcrypt(123456),
                'user_type' => 2,
                'address' => Str::random(10),
                'remember_token'=> Str::random(10),
            ],
            [
                'id' => '190001',
                'lname' => Str::random(10),
                'fname' => Str::random(10),
                'mname' => Str::random(10),
                'email' => Str::random(10).'@alkansya.com',
                'cell_num' => Str::random(10),
                'password' => bcrypt(190001),
                'user_type' => 0,
                'address' => Str::random(10),
                'remember_token'=> Str::random(10)
            ],
            [
                'id' => '190002',
                'lname' => Str::random(10),
                'fname' => Str::random(10),
                'mname' => Str::random(10),
                'email' => Str::random(10).'@alkansya.com',
                'cell_num' => Str::random(10),
                'password' => bcrypt(190002),
                'user_type' => 1,
                'address' => Str::random(10),
                'remember_token'=> Str::random(10)
            ]
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
