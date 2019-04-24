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
        // changed dummy information
        $users = [
            [
                'lname' => 'Cordero',
                'fname' => 'Brenda',
                'mname' => 'P.',
                'email' => 'brendacordero@gmail.com',
                'cell_num' => '09365265437',
                'password' => bcrypt('brendacordero@gmail.com'),
                'user_type' => 2,
                'address' => 'Barangay Poblacion Compostela Cebu City',
                'remember_token'=> Str::random(10),
            ],
            // [
            //     'id' => '190001',
            //     'lname' => 'Member',
            //     'fname' => 'Member',
            //     'mname' => 'Member',
            //     'email' => 'member@alkansya.com',
            //     'cell_num' => '09'.mt_rand(100000000, 999999999),
            //     'password' => bcrypt(123456),
            //     'user_type' => 0,
            //     'address' => 'Member',
            //     'remember_token'=> Str::random(10)
            // ],
            // [
            //     'id' => '190002',
            //     'lname' => 'Collector',
            //     'fname' => 'Collector',
            //     'mname' => 'Collector',
            //     'email' => 'collector@alkansya.com',
            //     'cell_num' => '09'.mt_rand(100000000, 999999999),
            //     'password' => bcrypt(123456),
            //     'user_type' => 1,
            //     'address' => 'Collector',
            //     'remember_token'=> Str::random(10)
            // ]
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
