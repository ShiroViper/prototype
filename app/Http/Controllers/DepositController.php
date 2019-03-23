<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
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

        return dd('test');

        $deposit = new Deposit;
        $deposit->member_id = Auth::user()->id;
        $deposit->payment_method = $request->input('payment_method');
        $deposit->start_date =  $request->input('start_date');
        

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

        // Schedule type as Deposit Schedule [1], Loan Schedule [2]
        $schedule->sched_type = 1;
        $schedule->save();
        
        /**
         * Schedule should be saved first in order for
         * the deposit to get its ID
         */
        $deposit->sched_id = $schedule->id;
        $deposit->save();

        /* Users Table update setup column after the user setup his/her account: use for toggleable navbar  */
        $setup = User::where('id', Auth::user()->id)->first();
        $setup->setup = 1;
        $setup->save();

        return redirect()->action('SchedulesController@index');
    }
}
