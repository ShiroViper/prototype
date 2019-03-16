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
        $sched_list = [];
        foreach ($schedules as $key => $schedule) {
            // Deposit Schedule type [1]
            if ($schedule->sched_type == 1) {
                $method = Deposit::where('member_id', '=', Auth::user()->id)->first();
                // return dd($schedule->payment_method);
                // Get the deposit table row of the logged in user
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
                                'color' => '#ff7043',
                                'textColor' => '#ff7043',
                                // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                                'description' => 'Wala',
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
                            // echo "<pre>".$key." ".$date->format('Y-m-d')."</pre>";
                            $sched_list[] = Calendar::event(
                                '__',
                                true,
                                $date->format('Y-m-d'),
                                $date->format('Y-m-d'),
                                $key,
                                [
                                    'color' => '#ff7043',
                                    'textColor' => '#ff7043',
                                    // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                                    'description' => 'Wala',
                                    // 'userId' => 'User ID '.$schedule->userId
                                ]
                            );
                        }
                    break;
                    case 3:
                       return dd('yearrr');
                    break;
                }
            } else {
                // Loan Schedule type [2]
                $sched_list[] = Calendar::event(
                    '__',
                    true,
                    new Carbon($schedule->start_date),
                    new Carbon($schedule->end_date.' +1 Day'),
                    $key,
                    [
                        'color' => '#ff7043',
                        'textColor' => '#ff7043',
                        // 'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                        'description' => 'Wala',
                        // 'userId' => 'User ID '.$schedule->userId
                    ]
                );
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
        ])->setOptions([
            'header' => [],
            'eventLimit' => 4,
        ]);

        if (Auth::user()->user_type == 2) {
            return view('users.admin.calendar')->with(compact('calendar_details'))->with('active', 'sched');
        } else {
            /**
             * If a member didn't set-up his/her payment
             * method yet
             * 
             * Get the member_id and return the first row
             */
            if (Deposit::where('member_id', '=', Auth::user()->id)->doesntExist()) {
                return view('users.member.dashboard')->with('setup', 'Please setup your account first')->with('active', 'dashboard');
            } else {
                return view('users.member.dashboard')->with(compact('calendar_details'))->with('active', 'dashboard')->with('setup', null);
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
