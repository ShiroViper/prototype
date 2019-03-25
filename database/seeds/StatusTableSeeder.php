<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusTableSeeder extends Seeder
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
                'id' => 1,
                'user_id' => 1,
            ],
            [
                'id'=> 2,
                'user_id' => 190001,
            ]
        ];

        foreach($users as $user) {
            Status::create($user);
        }
    }
}
