<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;

// Imports
use Auth;
use Validator;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use Calendar;
use App\User;
use App\Deposit;
use DB;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::get();
        $user = User::find(Auth::user()->id);

        $sched_list = [];
        /*
        foreach ($schedules as $key => $schedule) {
            // [1] Deposit Schedule type for a member
            if ($schedule->sched_type == 1 && ($schedule->user_id == Auth::user()->id) ) {
                return dd('here boi');
                $method = User::where([
                    ['member_id', '=', Auth::user()->id],
                    ['setup', '=', null]
                ])->first();
                // return dd($schedule->payment_method);
                // Get the deposit table row of the logged in user

                // If method user acccount is not found, redirect to setting up account
                return dd($method);
                if(!$method){
                    return view('users.member.dashboard')->with('setup', 'Please setup your account first')->with('active', 'dashboard');
                }

                // switch ($method->payment_method) {
                //     // Daily deposit payment basis
                //     case 1:
                //         $sched_list[] = Calendar::event(
                //             '__',
                //             true,
                //             new Carbon($schedule->start_date),
                //             new Carbon($schedule->end_date.' +1 Day'),
                //             $key,
                //             [
                //                 'color' => '#EF5450',
                //                 'textColor' => '#EF5450',
                //                 // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                //                 'description' => 'Daily Payment Deposit',
                //                 // 'userId' => 'User ID '.$schedule->userId
                //             ]
                //         );
                //     break;
                //     case 2:
                //         // $numOfWeek = $start->diffInWeeks($end);
                //         $interval = new DateInterval('P7D');
                //         // $dayOfWeek = Carbon::parse($start)->dayOfWeek;
                //         $start = new Carbon($schedule->start_date);
                //         $end = new Carbon($schedule->end_date.' +1 Day');
                //         $period = new DatePeriod($start, $interval, $end);
                //         foreach ($period as $key => $date) {
                //             // echo "<pre>".$key." ".$date->format('Y-m-d')."</pre>";
                //             $sched_list[] = Calendar::event(
                //                 '__',
                //                 true,
                //                 $date->format('Y-m-d'),
                //                 $date->format('Y-m-d'),
                //                 $key,
                //                 [
                //                     'color' => '#EF5450',
                //                     'textColor' => '#EF5450',
                //                     // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                //                     'description' => 'Weekly Payment Deposit',
                //                     // 'userId' => 'User ID '.$schedule->userId
                //                 ]
                //             );
                //         }
                //     break;
                //     case 3:
                //        return dd('monthly');
                //     break;
                // }
            } 
            
            else if ($schedule->sched_type == 2) {
                // [2] Show Loan Schedule
                $sched_list[] = Calendar::event(
                    '__',
                    true,
                    new Carbon($schedule->start_date),
                    new Carbon($schedule->end_date.' +1 Day'),
                    $key,
                    [
                        'color' => '#EF9950',
                        'textColor' => '#EF9950',
                        // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                        'description' => 'Loan Date',
                        // 'userId' => 'User ID '.$schedule->userId
                    ]
                );
            } 

            else if ($schedule->sched_type == 3) {
                // [3] Show Paid Loan Schedule
                $sched_list[] = Calendar::event(
                    '__',
                    true,
                    new Carbon($schedule->start_date),
                    new Carbon($schedule->end_date.' +1 Day'),
                    $key,
                    [
                        'color' => '#3FBC47',
                        'textColor' => '#3FBC47',
                        // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                        'description' => 'Paid Date',
                        // 'userId' => 'User ID '.$schedule->userId
                    ]
                );
            } 
            
            else {
                // kanang wala na juy choice
                $methods = Deposit::all();
                foreach ($methods as $method) {
                    switch ($method->payment_method) {
                        // Daily deposit payment basis
                        case 1:
                            $sched_list[] = Calendar::event(
                                '__',
                                true,
                                new Carbon($schedule->start_date),
                                new Carbon($schedule->end_date.' +1 Day'),
                                $key,
                                [
                                    'color' => '#EF5450',
                                    'textColor' => '#EF5450',
                                    // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                                    'description' => 'Daily Payment Deposit',
                                    // 'userId' => 'User ID '.$schedule->userId
                                ]
                            );
                        break;
                        case 2:
                            // $numOfWeek = $start->diffInWeeks($end);
                            $interval = new DateInterval('P7D');
                            // $dayOfWeek = Carbon::parse($start)->dayOfWeek;
                            $start = new Carbon($schedule->start_date);
                            $end = new Carbon($schedule->end_date.' +1 Day');
                            $period = new DatePeriod($start, $interval, $end);
                            foreach ($period as $key => $date) {
                                // return dd($schedule);
                                // echo "<pre>".$key." ".$date->format('Y-m-d')."</pre>";
                                $sched_list[] = Calendar::event(
                                    '__',
                                    true,
                                    $date->format('Y-m-d'),
                                    $date->format('Y-m-d'),
                                    $key,
                                    [
                                        'color' => '#EF5450',
                                        'textColor' => '#EF5450',
                                        // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                                        'description' => 'Weekly Payment Deposit',
                                        // 'userId' => 'User ID '.$schedule->userId
                                    ]
                                );
                            }
                        break;
                        case 3:
                           return dd('monthly');
                        break;
                    }
                }
            }
        }
        */
        foreach ($schedules as $key => $schedule) {
            switch ($schedule->sched_type) {
                // Collector's schedule instead of DEPOSIT
                case 1:

                break;

                // Loan Request
                case 2:
                $sched_list[] = Calendar::event(
                    '_',
                    true,
                    new Carbon($schedule->start_date),
                    new Carbon($schedule->end_date.' +1 Day'),
                    $key,
                    [
                        'color' => '#EF9950',
                        'textColor' => '#EF9950',
                        'description' => 'Loan Date',
                        // can add variables
                        'sample' => 'sample'
                    ]
                );
                break;

                // Paid Loan / Deposit
                case 3:
                    $sched_list[] = Calendar::event(
                        '__',
                        true,
                        new Carbon($schedule->start_date),
                        new Carbon($schedule->end_date.' +1 Day'),
                        $key,
                        [
                            'color' => '#3FBC47',
                            'textColor' => '#3FBC47',
                            'description' => 'Paid Date',
                        ]
                    );
                break;
            }
        }

        $calendar_details = Calendar::addEvents($sched_list)->setCallbacks([
            'eventRender' => 'function(event, element) {
                $(element).popover({
                    content: event.description,
                    trigger: "hover",
                    placement: "top",
                    container: "body"
                });
            }',
            'eventClick' => 'function(event) {
                if (event) {
                    alert("Clicked " + event.sample);
                }
            }'
        ])->setOptions([
            'header' => [],
            'eventLimit' => 4,
        ]);

        /**
         * If a member didn't set-up his/her payment
         * method yet
         * 
         * Get the member_id and return the first row
        */
        if (Auth::user()->user_type == 2) {
            return view('users.admin.calendar')->with(compact('calendar_details'))->with('active', 'sched');
        } else {
            if ($user = User::where([
                    ['id', '=', Auth::user()->id],
                    ['setup', '=', null]
                ])->first()) {
                return view('users.member.dashboard')->with('user', $user)->with('active', 'dashboard');
            } else {
                return view('users.member.dashboard')->with(compact('calendar_details'))->with('active', 'dashboard')->with('user', null);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
