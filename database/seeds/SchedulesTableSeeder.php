<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Schedule;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedules = [
            [
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(5),
            ],
            [
                'start_date' => Carbon::now()->addWeeks(2),
                'end_date' => Carbon::now()->addDays(5),
            ]
        ];

        foreach ($schedules as $schedule){
            Schedule::create($schedule);
        }
        // DB::table('schedules')->insert([
        //     'start_date' => Carbon::now(),
        //     'end_date' => Carbon::now()->addDays(5),
        // ]);
    }
}
