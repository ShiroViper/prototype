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
                'id' => '190001',
                'lname' => 'Cordero',
                'fname' => 'Brenda',
                'mname' => 'P.',
                'email' => 'brendacordero@gmail.com',
                'cell_num' => '09365265437',
                'password' => bcrypt('brendacordero@gmail.com'),
                'user_type' => 2,
                'street_number' => 'None',
                'barangay' => 'Barangay Poblacion',
                'city_town' => 'Compostela Cebu City',
                'province' => 'Cebu',
                'remember_token'=> Str::random(10),
            ],
            [
                'id' => '190002',
                'lname' => 'Cortes',
                'fname' => 'Jefriel',
                'mname' => 'C.',
                // 'referral' => 'Brenda Cordero-',
                'email' => 'cortes@gmail.com',
                'cell_num' => '09937763872',
                'password' => bcrypt('cortes@gmail.com'),
                'user_type' => 1,
                'street_number' => 'None',
                'barangay' => 'Barangay Poblacion',
                'city_town' => 'Compostela Cebu City',
                'province' => 'Cebu',
                'remember_token'=> Str::random(10),
                'setup' => 1
            ],
            [
                'id' => '190003',
                'lname' => 'Lebrias',
                'fname' => 'Kath',
                'mname' => 'L.',
                // 'referral' => 'Brenda Cordero',
                'email' => 'lebrias@gmail.com',
                'cell_num' => '09827384772',
                'password' => bcrypt('lebrias@gmail.com'),
                'user_type' => 0,
                'street_number' => 'None',
                'barangay' => 'Barangay Poblacion',
                'city_town' => 'Compostela Cebu City',
                'province' => 'Cebu',
                'remember_token'=> Str::random(10),
                'setup' => 1
            ],
            [
                'id' => '190004',
                'lname' => 'Bacus',
                'fname' => 'Joel',
                'mname' => 'K.',
                'email' => 'bacus@gmail.com',
                // 'referral' => 'Brenda Cordero',
                'cell_num' => '09375628936',
                'password' => bcrypt('bacus@gmail.com'),
                'user_type' => 0,
                'street_number' => 'None',
                'barangay' => 'Barangay Poblacion',
                'city_town' => 'Compostela Cebu City',
                'province' => 'Cebu',
                'remember_token'=> Str::random(10),
                'setup' => 1
            ],
            [
                'id' => '190005',
                'lname' => 'Cueva',
                'fname' => 'Angela',
                'mname' => 'B.',
                'email' => 'cueva@gmail.com',
                'cell_num' => '09756820081',
                'password' => bcrypt('cueva@gmail.com'),
                'user_type' => 0,
                'street_number' => 'None',
                'barangay' => 'Barangay Poblacion',
                'city_town' => 'Compostela Cebu City',
                'province' => 'Cebu',
                'remember_token'=> Str::random(10),
                'setup' => 1
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
