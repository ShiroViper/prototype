<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Deposit;
use App\Schedule;
use Calendar;
use Auth;
use Carbon\Carbon;

use DateTime;
use DatePeriod;
use DateInterval;

class DepositController extends Controller
{
    public function create(Request $request) 
    {
        /**
         * Creates the deposit for the user
         * and the schedule
         */
        $this->validate($request, [
            'payment_method' => ['required'],
            'start_date' => ['required']
        ]);

        $deposit = new Deposit;
        $deposit->member_id = Auth::user()->id;
        $deposit->payment_method = $request->input('payment_method');
        $deposit->start_date =  $request->input('start_date');
        $deposit->save();

        $schedule = new Schedule;
        $schedule->deposit()->associate($deposit);
        $schedule->user_id = $schedule->deposit->member_id;

        // Convert the date into carbon
        $start = new Carbon($schedule->deposit->start_date);

        /**
         * Set the end date
         * For example: End date is Christmas this year
         * 
         * null = THIS [year, month, etc..]
         */
        $end = Carbon::createFromDate(null, 12, 25);

        $schedule->start_date =  $start;
        $schedule->end_date = $end;
        $schedule->save();

        $numOfWeek = $start->diffInWeeks($end);
        $interval = new DateInterval('P7D');
        $dayOfWeek = Carbon::parse($start)->dayOfWeek;
        $period = new DatePeriod($start, $interval, $end);
        foreach ($period as $key => $date) {
            echo "<pre>".$key." ".$date->format('Y-m-d')."</pre>";
        }

        // return dd($schedule, $numOfWeek, $dayOfWeek);
        
        return redirect()->action('SchedulesController@index');
    }
}
