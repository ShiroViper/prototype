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
use App\Loan_Request;
use App\Status;


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
                $savings = Status::where('user_id', Auth::user()->id)->first();
                $patronage = Status::where('user_id', Auth::user()->id)->first();
                // dd($patronage->savings);
                $loan = Loan_Request::where([['user_id',Auth::user()->id], ['confirmed', 1], ['received',1], ['paid', NULL]])->first();

                // return view('users.member.status')->with('loan', $loan)->with('savings', $savings)->with('patronage', $patronage)->with('active', 'status');

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
                                $user = User::find($schedule->user_id);                 // Get the instance of a user by its ID
                                $lr = Loan_Request::where('user_id', '=', $schedule->user_id)->has('user')->first();
                                // Get the loan request of that certain user
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'loan_id' => $lr->id,
                                        'start' => $schedule->start_date,
                                        'end' => $schedule->end_date,
                                        'color' => '#F87930',
                                        'textColor' => '#F87930',
                                        'description' => 'Loan request from '.$user->lname.', '.$user->fname.' '.$user->mname,
                                        'sched_type' => $schedule->sched_type,
                                        'user_id' => $user->id,
                                        'lname' => $user->lname,
                                        'fname' => $user->fname,
                                        'mname' => $user->mname,
                                        'email' => $user->email,
                                        'address' => $user->address,
                                        'cell' => $user->cell_num,
                                        'amount' => $lr->loan_amount,
                                        'dp' => $lr->days_payable,
                                        'paid' => $lr->paid,
                                        'received' => $lr->received
                                    ]
                                );
                                // $test = Carbon::now()->format('N');

                                // return dd($test);
                                // return dd($schedule->id, $lr->id);
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
                                        'sched_type' => $schedule->sched_type,
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
                        'eventClick' => "function(event) {
                            $('.info-card').show();
                            $('.duty-card').hide();
                            $('.admin-calendar-info').show();
                            $('.info-sub-title').hide();
                            $('.info-title').text(function() {
                                switch (event.sched_type) {
                                    case 1: break;
                                    case 2:
                                        return 'Loan Request';
                                    break;
                                    case 3:
                                        return 'User Payment';
                                    break;
                                }
                            });
                            $('.info-card').removeClass('admin-calendar-paid-dates admin-calendar-loan-dates').toggleClass(function(){
                                switch (event.sched_type) {
                                    case 1: break;
                                    case 2:
                                        return 'admin-calendar-loan-dates';
                                    break;
                                    case 3:
                                        return 'admin-calendar-paid-dates';
                                    break;
                                }
                            }).addClass('text-white');
                            $('.admin-calendar-info .info-name').html('<small>Name</small><h6>'+event.lname+', '+event.fname+' '+event.mname+'</h6>');
                            $('.admin-calendar-info .info-cell').html('<small>Contact Details</small><h6>'+event.cell+'</h6>');
                            $('.admin-calendar-info .info-email').html('<small>Email-address</small><h6>'+event.email+'</h6>');
                            $('.admin-calendar-info .info-address').html('<small>Address</small><h6>'+event.address+'</h6>');
                            $('.admin-calendar-info .info-email').html('<small>Email-address</small><h6>'+event.email+'</h6>');
                            $('.admin-calendar-info .info-amt').html('<small>Amount Loaned</small><h6>'+event.amount+'</h6>');
                            $('.admin-calendar-info .info-dp').html('<small>Days payable</small><h6>'+event.dp+'</h6>');
                            $('.admin-calendar-info .info-divider').html('<hr>');

                            if (event.paid) {
                                $('.admin-calendar-info .info-paid').html('<h6>Payment Done</h6>');
                            } else {
                                $('.admin-calendar-info .info-paid').html('<h6 class=\"font-italic alert alert-warning border\">Payment still ongoing</h6>');
                            }

                           if (event.received) {
                                $('.admin-calendar-info .info-status').html('<h6 class=\"font-italic alert alert-success border text-center\">Money already sent</h6>');
                            } else {
                                $('.admin-calendar-info .info-status').html('<a class=\"btn btn-primary assign-btn btn-block\" data-sched='+event.loan_id+' href=\"/admin/process/'+event.loan_id+'/edit\" role=\"button\">Assign collector</a>');
                            }
                        }",
                        'eventMouseout' => 'function (event) {
                            $(this).removeClass("admin-calendar-selected-event");
                        }',
                        'select' => 'function(startDate, endDate) {
                            $(".duty-sub-title").hide();
                            $(".admin-calendar-info").hide();
                            $(".info-card").removeClass("admin-calendar-paid-dates admin-calendar-loan-dates text-white");
                            $(".info-title").text("Event Details");
                            $(".info-sub-title").show().text("Click on an event to view more details");

                            $("input[name=av_start]").val(moment(startDate).format("YYYY-MM-DD"));
                            $("input[name=av_end]").val(moment(endDate).format("YYYY-MM-DD"));
                        }',
                    ])->setOptions([
                        'plugins' => [ 'interaction', 'dayGrid'],
                        'header' => [],
                        'eventLimit' => 2,
                        'selectable' => true,
                    ]);
            break;
        }

        /**
         * If a member didn't set-up his/her payment
         * method yet
         * 
         * Get the member_id and return the first row
        */
        if (Auth::user()->user_type == 2) {
            $collectors = User::where('user_type', '=', 1)->get();
            // dd($collectors);
            return view('users.admin.calendar')->with(compact('calendar_details'))->with('active', 'sched')->with('collectors', $collectors);
        } else {
            if ($user = User::where([
                    ['id', '=', Auth::user()->id],
                    ['setup', '=', null]
                ])->first()) {
                return view('users.member.dashboard')->with('user', $user)->with('active', 'dashboard');
            } else {
                // return view('users.member.status')->with('loan', $loan)->with('savings', $savings)->with('patronage', $patronage)->with('active', 'status');
                // return dd($savings);
                return view('users.member.dashboard')->with(compact('calendar_details'))->with('active', 'dashboard')->with('user', null)->with('loan', $loan)->with('savings', $savings)->with('patronage', $patronage);
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
