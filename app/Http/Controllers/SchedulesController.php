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
use App\Transaction;


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
                        'eventLimit' => 2,
                        // 'selectable' => true
                    ]);
                } else {
                    $sched_list = [];
                    foreach ($schedules as $key => $schedule) {
                        switch ($schedule->sched_type) {                                // What type of schedules that were fetched
                            case 1:                                                     // Deposit schedule type 
                                $user = User::find($schedule->user_id);                 // Get the user info
                                $transaction = Transaction::where('sched_id', $schedule->id)->first();    // Get the transaction info
                                // dd($transaction);
                                $collector = User::where('id', $transaction->collector_id)->first();// Get the collector info
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#00CC66',
                                        'textColor' => '#00CC66',
                                        'description' => 'Date',
                                        'sched_type' => $schedule->sched_type,
                                        'user_id' => $user->id,
                                        'lname' => $user->lname,
                                        'fname' => $user->fname,
                                        'mname' => $user->mname,
                                        'email' => $user->email,
                                        'description' => 'You had paid your deposit to '.$collector->lname.', '.$collector->fname.' with an amount of ₱'.$transaction->amount.'.',
                                    ]
                                );
                            break;
                            case 2:                                                     // Loan Request schedule type
                                $user = User::find($schedule->user_id);                 // Get the instance of a user by its ID
                                $lr = Loan_Request::where('user_id', '=', $schedule->user_id)->has('user')->first();
                                $interval = new DateInterval('P1M');
                                $period = new DatePeriod(new Carbon($schedule->start_date), $interval, new Carbon($schedule->end_date));
                                // dd($lr);
                                foreach ($period as $date) {
                                    $sched_list[] = Calendar::event(
                                        '_',
                                        true,
                                        $date->format('Y-m-d'),
                                        $date->format('Y-m-d'),
                                        $key,
                                        [
                                            'color' => '#F87930',
                                            'textColor' => '#F87930',
                                            'description' => 'Loan Date',
                                            'sched_type' => $schedule->sched_type,
                                            'description' => $lr->paid == null ? 
                                            '<div class="font-italic alert alert-warning border h6">Payment still ongoing</div>
                                            <div class="row text-left">
                                                <div class="col">Remaining balance <b>₱'.$lr->balance.'</b></div>
                                                <div class="col">Start date: '.Carbon::parse($schedule->start_date)->format("F j, Y").'</div>
                                            </div>
                                            <div class="row text-left">
                                                <div class="col">
                                                    <b>₱'.
                                                        round($per_month = ($lr->loan_amount * 0.06 * $lr->days_payable + $lr->loan_amount) / $lr->days_payable, 2)
                                                    .'</b> per month</div>
                                                <div class="col"> End Date: '.Carbon::parse($schedule->end_date)->format("F j, Y").'</div>
                                            </div>' : 
                                            '<div class="font-italic alert alert-success border h6">Loan request paid</div>
                                            <div></div>
                                            <div>Loan Amount ₱'.$lr->loan_amount.'</div>',
                                        ]
                                    );
                                }
                            break;
                            case 3:                                                     // Payment schedule type
                                $user = User::find($schedule->user_id);                 // Get the user info
                                $transaction = Transaction::where([
                                    ['sched_id', '=', $schedule->id],
                                    ['trans_type', '=', 3]
                                ]
                                )->first();    // Get the transaction info
                                $collector = User::where('id', $transaction->collector_id)->first();// Get the collector info
                                $lr = Loan_Request::where('user_id', '=', $schedule->user_id)->has('user')->first();
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
                                        'sched_type' => $schedule->sched_type,
                                        'description' => 'You had paid your loan payment to '.$collector->lname.', '.$collector->fname.' with an amount of ₱'.$transaction->amount.'.'
                                    ]
                                );
                            break;
                        }
                    }
                    // dd($collector->lname ? 'naa ko dinhi' : 'wala lagi  ');
                //     $('html, body').animate({
                //     scrollTop: $("#div1").offset().top
                // }, 2000);
                    $calendar_details = Calendar::addEvents($sched_list)->setCallbacks([
                        'eventRender' => 'function(event, element) {
                            $(element).popover({
                                content: function() {
                                        switch (event.sched_type) {
                                        case 1: 
                                            return "Deposit payment";
                                        break;
                                        case 2:
                                            return "Loan Request";
                                        break;
                                        case 3:
                                            return "Loan Payment";
                                        break;
                                    }
                                },
                                trigger: "hover",
                                placement: "top",
                                container: "body"
                            });
                        }',
                        'eventClick' => 'function(event) {
                            $(".info-sub-title").hide();
                            $(".info-title").html(function() {
                                switch (event.sched_type) {
                                    case 1: 
                                        return "<b>Deposit</b>";
                                    break;
                                    case 2:
                                        return "<b>Loan Request</b>";
                                    break;
                                    case 3:
                                        return "<b>Loan Payment</b>";
                                    break;
                                }
                            });
                            $(".info-card").removeClass("calendar-paid-dates calendar-loan-dates text-white").addClass(function(){
                                switch (event.sched_type) {
                                    case 1: 
                                        return "calendar-paid-dates text-white";
                                    break;
                                    case 2:
                                        return "calendar-loan-dates text-white";
                                    break;
                                    case 3:
                                        return "calendar-paid-dates text-white";
                                    break;
                                }
                            });
                            $(".info-details").html(event.description);
                        }'
                    ])->setOptions([
                        'header' => [],
                        'eventLimit' => 3,
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
                                $user = User::find($schedule->user_id);
                                $transaction = Transaction::where('sched_id', $schedule->id)->first();    // Get the transaction info
                                // dd($transaction);
                                $collector = User::where('id', $transaction->collector_id)->first();// Get the collector info
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#00CC66',
                                        'textColor' => '#00CC66',
                                        'description' => 'Deposit payment of <b>₱'.$transaction->amount.'</b> from <b>'.$user->lname.', '.$user->fname.'</b> to <b>'.$collector->lname.', '.$collector->fname.'</b>',
                                        'sched_type' => $schedule->sched_type,
                                        'user_id' => $user->id,
                                        'lname' => $user->lname,
                                        'fname' => $user->fname,
                                        'mname' => $user->mname,
                                        'email' => $user->email,
                                        'address' => $user->address,
                                        'cell' => $user->cell_num,
                                    ]
                                );
                            break;
                            case 2:                                                     // Loan Request schedule type
                                $user = User::find($schedule->user_id);                 // Get the instance of a user by its ID
                                $lr = Loan_Request::where('user_id', '=', $schedule->user_id)->has('user')->first();
                                // Get the loan request of that certain user
                                $interval = new DateInterval('P1M');
                                $period = new DatePeriod(new Carbon($schedule->start_date), $interval, new Carbon($schedule->end_date));
                                foreach ($period as $date) {
                                    // $test[] = $date->format('Y-m-d');
                                    $sched_list[] = Calendar::event(
                                        '_',
                                        true,
                                        $date->format('Y-m-d'),
                                        $date->format('Y-m-d'),
                                        $key,
                                        [
                                            'loan_id' => $lr->id,
                                            // 'start' => $schedule->start_date,
                                            // 'end' => $schedule->end_date,
                                            // 'start' => $date->format('Y-m-d'),
                                            // 'end' => $date->format('Y-m-d'),
                                            'color' => '#F87930',
                                            'textColor' => '#F87930',
                                            'description' => 'Loan request from '.$user->lname.', '.$user->fname.' '.$user->mname,
                                            'sched_type' => $schedule->sched_type,
                                            'user_id' => $user->id,
                                            'lname' => $user->lname,
                                            'fname' => $user->fname,
                                            'mname' => $user->mname ? $user->mname : '',
                                            'email' => $user->email,
                                            'address' => $user->address,
                                            'cell' => $user->cell_num,
                                            'amount' => $lr->loan_amount,
                                            'dp' => $lr->days_payable,
                                            'paid' => $lr->paid,
                                            'received' => $lr->received
                                        ]
                                    );
                                }
                                // $test = Carbon::now()->format('N');
                                // return dd($sched_list);
                                // return dd($schedule->id, $lr->id);
                            break;
                            case 3:                                                     // Payment schedule type
                                $user = User::find($schedule->user_id);
                                $transaction = Transaction::where('sched_id', $schedule->id)->first();    // Get the transaction info
                                // dd($transaction);
                                $collector = User::where('id', $transaction->collector_id)->first();// Get the collector info
                                $sched_list[] = Calendar::event(
                                    '_',
                                    true,
                                    new Carbon($schedule->start_date),
                                    new Carbon($schedule->end_date),
                                    $key,
                                    [
                                        'color' => '#00CC66',
                                        'textColor' => '#00CC66',
                                        'description' => 'Loan payment of <b>₱'.$transaction->amount.'</b> from <b>'.$user->lname.', '.$user->fname.'</b> to <b>'.$collector->lname.', '.$collector->fname.'</b>',
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
                                content: function() {
                                    switch (event.sched_type) {
                                    case 1: 
                                        return "Deposit payment";
                                    break;
                                    case 2:
                                        return "Loan Request";
                                    break;
                                    case 3:
                                        return "Loan Payment";
                                    break;
                                    }
                                },
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
                            $('.info-title').html(function() {
                                switch (event.sched_type) {
                                    case 1: 
                                        return '<b>Member Deposit</b>';
                                    break;
                                    case 2:
                                        return '<b>Loan Request</b>';
                                    break;
                                    case 3:
                                        return '<b>Loan Payment</b>';
                                    break;
                                }
                            });
                            $('.info-card').removeClass('calendar-paid-dates calendar-loan-dates').addClass(function(){
                                switch (event.sched_type) {
                                    case 1: 
                                        return 'calendar-paid-dates';
                                    break;
                                    case 2:
                                        return 'calendar-loan-dates';
                                    break;
                                    case 3:
                                        return 'calendar-paid-dates';
                                    break;
                                }
                            }).addClass('text-white');

                            switch (event.sched_type) {
                                case 1:
                                    $('.admin-calendar-info').hide();
                                    $('.admin-calendar-payment').show();
                                    $('.info-details').html(event.description);
                                break;
                                case 2:
                                    $('.admin-calendar-info').show();
                                    $('.admin-calendar-payment').hide();
                                    $('.admin-calendar-info .info-name').html('<small>Name</small><h6>'+event.lname+', '+event.fname+' '+event.mname+'</h6>');
                                    $('.admin-calendar-info .info-cell').html('<small>Contact Details</small><h6>'+event.cell+'</h6>');
                                    $('.admin-calendar-info .info-email').html('<small>Email-address</small><h6>'+event.email+'</h6>');
                                    $('.admin-calendar-info .info-address').html('<small>Address</small><h6>'+event.address+'</h6>');
                                    $('.admin-calendar-info .info-email').html('<small>Email-address</small><h6>'+event.email+'</h6>');
                                    $('.admin-calendar-info .info-amt').html('<small>Amount Loaned</small><h6>'+event.amount+'</h6>');
                                    $('.admin-calendar-info .info-dp').html('<small>Payable</small><h6>'+event.dp+' Month/s</h6>');

                                    if (event.paid) {
                                        $('.admin-calendar-info .info-paid').html('<h6 class=\"font-italic alert alert-success border\">Payment Done</h6>');
                                    } else {
                                        $('.admin-calendar-info .info-paid').html('<h6 class=\"font-italic alert alert-warning border\">Payment still ongoing</h6>');
                                    }

                                    if (event.received) {
                                        $('.admin-calendar-info .info-status').html('<h6 class=\"font-italic alert alert-success border text-center\">Money already sent</h6>');
                                    } else {
                                        $('.admin-calendar-info .info-status').html('<a class=\"btn btn-primary assign-btn btn-block\" data-sched='+event.loan_id+' href=\"/admin/process/'+event.loan_id+'/edit\" role=\"button\">Assign collector</a>');
                                    }
                                break;
                                case 3:
                                    $('.admin-calendar-info').hide();
                                    $('.admin-calendar-payment').show();
                                    $('.info-details').html(event.description);
                                break;
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
                        'eventLimit' => 3,
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
