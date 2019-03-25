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
        switch (Auth::user()->user_type) {
            case 0: // Member login
                $schedules = Schedule::where('user_id', '=', Auth::user()->id)->get(); // Get the schedules related to the user
                if ($schedules->count() == 0) {                                        // This user has no schedules
                    $sched_list = [];                                                  // return an empty calendar
                    $calendar_details = Calendar::addEvents($sched_list)->setCallbacks([
                        'eventRender' => 'function(event, element) {
                            $(element).popover({
                                content: event.description,
                                trigger: "hover",
                                placement: "top",
                                container: "body"
                            });
                        }'
                        
                    ])->setOptions([
                        'header' => [],
                        'eventLimit' => 4,
                        // 'selectable' => true
                    ]);
                } else {
                    $sched_list = [];
                    foreach ($schedules as $key => $schedule) {
                        switch ($schedule->sched_type) {                                // What type of schedules that were fetched
                            case 1:                                                     // Deposit schedule type 
                            break;
                            case 2:                                                     // Loan Request schedule type
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#F87930',
                                        'textColor' => '#F87930',
                                        'description' => 'Loan Date',
                                    ]
                                );
                            break;
                            case 3:                                                     // Payment schedule type
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#00CC66',
                                        'textColor' => '#00CC66',
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
                        }'
                    ])->setOptions([
                        'header' => [],
                        'eventLimit' => 4,
                        // 'selectable' => true
                    ]);
                }
            break;

            case 1: // Collector login
            break;

            case 2: // Admin login
                $schedules = Schedule::get();
                $sched_list = [];
                    foreach ($schedules as $key => $schedule) {
                        switch ($schedule->sched_type) {                                // What type of schedules that were fetched
                            case 1:                                                     // Deposit schedule type 
                            break;
                            case 2:                                                     // Loan Request schedule type
                                $user = User::find($schedule->user_id);                // Get the instance of a user by its ID
                                // return dd($user);
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#F87930',
                                        'textColor' => '#F87930',
                                        'description' => 'Loan request from '.$user->lname.', '.$user->fname.' '.$user->mname,
                                        'user_id' => $user->user,
                                        'lname' => $user->lname,
                                        'fname' => $user->fname,
                                        'mname' => $user->mname,
                                        'email' => $user->email,
                                        'address' => $user->address,
                                        'cell' => $user->cell_num
                                    ]
                                );
                            break;
                            case 3:                                                     // Payment schedule type
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#00CC66',
                                        'textColor' => '#00CC66',
                                        'description' => 'Payment made by '.$user->lname.', '.$user->fname.' '.$user->mname,
                                        'user_id' => $user->user,
                                        'lname' => $user->lname,
                                        'fname' => $user->fname,
                                        'mname' => $user->mname,
                                        'email' => $user->email,
                                        'address' => $user->address,
                                        'cell' => $user->cell_num
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
                            $(".info-title").text("");
                            $(".admin-calendar-info .info-name").html("<p>Name</p><h5>"+event.lname+", "+event.fname+" "+event.mname+"</h5>");
                            $(".admin-calendar-info .info-cell").html("<p>Contact Number</p><h5>"+event.cell+"</h5>");
                            $(".admin-calendar-info .info-email").html("<p>Email-address</p><h5>"+event.email+"</h5>");
                            $(".admin-calendar-info .info-address").html("<p>Address</p><h5>"+event.address+"</h5>");
                        }'
                    ])->setOptions([
                        'header' => [],
                        'eventLimit' => 4,
                        // 'selectable' => true
                    ]);
            break;
        }

        // if (event) {
        //     alert("Clicked "+event.lname+", "+event.fname+" "+event.mname+" "+event.cell+" "+event.address+" "+event.email);
        // }

        // return dd('Im out');
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
                        'color' => '#F87930',
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

        /*
        switch ($schedule->sched_type) {
            // Collector's schedule instead of DEPOSIT
            case 1:

            break;

            // Loan Request
            case 2:
                // If the logged user is the ADMINISTRATOR
                if (Auth::user()->user_type == 2) {
                    $user = User::where('id', '=', $schedule->user_id)->first();
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
                            // get user info
                            'user_id' => $schedule->user_id,
                            'lname' => $user->lname,
                            'fname' => $user->fname,
                            'mname' => $user->mname,
                        ]
                    );
                } else {
                    if ($schedule->user_id == Auth::user()->id) {
                        return dd($schedule->user_id, Auth::user()->id, 'on Case 2');
                    }
                    
                    // $sched_list[] = Calendar::event(
                    //     '_',
                    //     true,
                    //     new Carbon($schedule->start_date),
                    //     new Carbon($schedule->end_date.' +1 Day'),
                    //     $key,
                    //     [
                    //         'color' => '#EF9950',
                    //         'textColor' => '#EF9950',
                    //         'description' => 'Loan Date',
                    //     ]
                    // );
                }
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
        
        foreach ($schedules as $key => $schedule) {
            switch (Auth::user()->user_type) {                              // What is the role of the CURRENT LOGGED IN USER
                case 0:                                                     // If the current user is a MEMEBER
                    switch ($schedule->sched_type) {                        // What type of Schedule will be shown
                        case 1: break;                                      // Deposit

                        case 2:                                             // Loan Request schedule type
                            if ($schedule->user_id == Auth::user()->id) {   // Check if schedule user id and user's id the same
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
                            } else {
                                break;
                            }
                        break;

                        // Payment
                        case 3:
                        break;

                        default:                                             // Only show the calendar
                        return dd('default bai');
                        $sched_list[] = Calendar::event(
                            '__',
                            true,
                            new Carbon($schedule->start_date),
                            new Carbon($schedule->end_date.' +1 Day'),
                            $key,
                            []
                        );
                    }
                break;

                case 1:
                break;

                case 2:
                break;
            }
        }
        */

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
