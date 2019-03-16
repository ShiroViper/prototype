<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Deposit;
use App\Schedule;
use Calendar;
use Auth;

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
        // $deposit->save();

        $schedule = new Schedule;
        $schedule->deposit()->associate($deposit);

        return dd($schedule, $deposit);


        return redirect()->action('SchedulesController@index');
    }
}
