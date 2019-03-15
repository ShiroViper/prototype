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
use Calendar;
//Make a model

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
            $sched_list[] = Calendar::event(
                '__',
                // 'Event ID # '.$schedule->loanRequest->loan_amount,
                true,
                new DateTime($schedule->start_date),
                // new DateTime($schedule->end_date),
                new DateTime($schedule->end_date.' +1 Day'),
                $key,
                [
                    'color' => '#ff7043',
                    'textColor' => '#ff7043',
                    'description' => 'Loaned ₱'.$schedule->loanRequest->loan_amount.' due on '.date('F d, Y', strtotime($schedule->end_date)),
                    // 'userId' => 'User ID '.$schedule->userId
                ]
            );
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
        }

        else {


            return view('users.member.dashboard')->with(compact('calendar_details'))->with('active', 'dashboard');
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
