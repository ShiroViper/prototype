<?php

namespace App\Http\Controllers;

use App\Loan_Request;
use App\Loan_Process;
use Illuminate\Http\Request;

// Add-on (just testing)
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Schedule;
use App\Status;
use App\Comment;
use App\Process;
use App\Distribution;

class LoanRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->user_type == 2) {
            // Pending_cancel for cancellation of accounts
            $pending_cancel = Comment::join('status', 'status.user_id', '=', 'comments.user_id')->join('users', 'users.id', '=', 'comments.user_id')->select('comments.id', 'savings', 'patronage_refund', 'comments.created_at', 'comments', 'lname', 'mname', 'fname')->whereNotNull('comments.user_id')->whereNull('comments.confirmed')->orderBy('comments.updated_at', 'asc')->paginate(5);
            $lr = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNotNull('confirmed')->paginate(5);
            $pending = Loan_Request::join('comments', 'request_id', '=', 'loan_request.id')->join('users', 'users.id', '=', 'loan_request.user_id')->select( 'loan_request.id', 'lname', 'mname', 'fname', 'loan_request.created_at', 'loan_amount', 'days_payable', 'comments')->orderBy('loan_request.created_at', 'desc')->whereNull('loan_request.confirmed')->paginate(5);
            // dd($pending);

            return view('users.admin.requests')->with('pending_cancel', $pending_cancel)->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');

        } else {
            $lr = Loan_Request::orderBy('updated_at', 'desc')->where('user_id', Auth::user()->id)->whereNotNull('confirmed')->paginate(5);
            $pending = Loan_Request::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->where('confirmed', NULL)->paginate(5);
            $unpaid = Loan_Request::where('user_id', Auth::user()->id)->whereNull('paid')->orWhere('paid', false)->first();
            $distribution = Distribution::join('status', 'status.user_id', '=', 'distribution.user_id')->where([['distribution.user_id', Auth::user()->id], ['confirmed', null]])->first();
            // dd($distribution);
            
            // for transferring money to member
            $pending_mem_receive = Process::join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'collector_id')->select('processes.id', 'transfer', 'request_id', 'collector_id', 'processes.updated_at','lname', 'fname','mname', 'loan_amount')->where([['user_id', Auth::user()->id],['transfer',3]])->orderBy('updated_at', 'asc')->paginate(5);
                
            // table processes column confirmed if the member confirm the member has successfully gave the money to colletor 
            $pending_mem_con = DB::table('transactions')
                ->join('users', 'users.id', '=', 'collector_id')
                ->select('transactions.id', 'amount','lname','fname', 'mname', 'trans_type', 'transactions.updated_at')
                ->where('confirmed', NULL)->where('member_id', Auth::user()->id)->orderBy('updated_at', 'asc')->paginate(5);

            /* This for finding duplicate token in table */
            $token = Str::random(10);
            $check_token = Process::select('token')->get();
            for ($x = 0; $x < count($check_token); $x++){
                if($check_token[$x]->token == $token){
                    $token = Str::random(10);
                }
            }
            // ================================================

        //    dd($pending_mem_con, Auth::user()->id);
            return view('users.member.requests')->with('token', $token)->with('distribution', $distribution)->with('pending_mem_con',$pending_mem_con)->with('pending_mem_receive', $pending_mem_receive)->with('unpaid', $unpaid)->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Comment::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================

        // example 3 months
        $m = 3;
        //This check every end year
        $months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12);
        $get_end_month = array_slice($months, date('n'));
        $count_end_month = count($get_end_month) - 1;

        $name = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November');
        $get_end_name = array_slice($name, date('n'));

        $paid = Loan_Request::where([['user_id', Auth::user()->id], ['paid', null]])->orwhere([['user_id', Auth::user()->id], ['paid_using_savings', null]])->first();

        $status = Status::where('user_id', Auth::user()->id)->first();
        $current_savings = Status::where('user_id', Auth::user()->id)->first();
        return view('users.member.loan')->with('token', $token)->with('get_end_name', $get_end_name)->with('count_end_month', $count_end_month)->with('get_end_month', $get_end_month)->with('status', $status)->with('paid', $paid)->with('active', 'loan')->with('savings', $current_savings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // This check if the token is duplicate or not after the form is saved, tricks the user that he/she store only one form. (several clicks in the button  )
        if(Comment::where('token', $request->token)->first()){
            return redirect()->action('LoanRequestsController@index');
        }

        //This check every end year
        $months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12);
        $get_end_month = array_slice($months, date('n'));
        $count_end_month = count($get_end_month) - 1;

        $status = Status::where('user_id', Auth::user()->id)->first();
        // check if member savings is null or less than 200 
        if($status->savings == null || $status->savings < 200){
            // check if reason is not emergency loan
            if($request->reason != 'For Emergency Use'){
                return redirect()->back()->with('error', 'Request loan available at least ₱ 200 savings ')->withInput(Input::except('pass'));
            }
        // check if amount is greater than current savings and reason is not emergency loan
        }else if($request->amount > $status->savings && $request->reason != 'For Emergency Use'){
            return redirect()->back()->with('error', 'Requested loan should be less than or equal to savings')->withInput(Input::except('pass'));
        // check if months payable is greater than the current end year
        }else if($request->months > $count_end_month ){
            return redirect()->back()->with('error', 'Months payable should not beyond the current end year')->withInput(Input::except('pass'));
        // check if password is not match
        }else if(!Hash::check($request->pass, Auth::user()->password)){
            return redirect()->back()->with('error', 'Wrong Password')->withInput(Input::except('pass'));
        }

        // dd($request);
        
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters',
            'numeric' => 'Please use only Numbers'
        ];

        $this->validate($request, [
            'amount' => ['required', 'numeric', 'min:200'],   
            'reason' => ['required', 'string'],
            'other' => ['nullable'],
            'pass' => ['required'],
            'months' => ['required', 'numeric', 'min:1', 'max:12']
        ], $messages);
        // return 'hji';

        // Loan * .06 = monthly interest * months payable = interest to pay + loan amount = loan to pay 
        // example: 1500 * 0.06 = 90 * 4 = 360 + 1500 = 1860
        $loan_to_pay = $request->amount * 0.06 * $request->months + $request->amount;

        $lr = new Loan_Request;
        $lr->loan_amount = $request->input('amount');
        $lr->days_payable = $request->input('months');
        $lr->balance = $loan_to_pay;
        $lr->get = 0;
        $lr->user_id = Auth::user()->id;
        $lr->per_month_amount = $loan_to_pay / $lr->days_payable;

        // sched_id is NULL for now since it still hasn't been approved
        $lr->sched_id = null;
        // return dd($lr);
        $lr->save();
        
        // Create a new comment row for every loan request
        $comment = New Comment;
        $comment->request_id = $lr->id;
        if($request->reason == 3){
            $comment->comments = $request->other;
        }else{
            $comment->comments = $request->reason;
        }
        $comment->token = $request->token; // prevent from being spammed
        $comment->save();

        return redirect()->action('LoanRequestsController@index')->with('success', 'Request sent');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loan_Request  $loan_Request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $lr = Loan_Request::orderBy('updated_at', 'desc')->where('user_id', Auth::user()->id)->whereNotNull('confirmed')->paginate(10);
        // $pending = Loan_Request::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->paginate(5);

        // $loan = Loan_Request::find($id);

        // // return dd($loan);

        // return view('users.member.requests')->with('loan', $loan)->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan_Request  $loan_Request
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan_Request $loan_Request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan_Request  $loan_Request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan_Request $loan_Request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan_Request  $loan_Request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rq = Loan_Request::find($id);
        // if $rq is equal to null redirect and return error otherwise continue
        if ($rq == NULL){            
            return redirect()->route('member-requests')->with('error', 'Loan Request Not Found');
        }
        // This condition acts as guard bugs found that the member can cancel even the admin accept the request when the member dont refresh the page
        if($rq->confirmed == TRUE){
            return redirect()->route('member-requests')->with('error', 'This request already accepted by the admin');
        }
        $rq->delete();
        return redirect()->route('member-requests')->with('success', 'Request removed successfully');
    }

    public function accept($id) 
    {
        $rq = Loan_Request::find($id);
        $rq->confirmed = true;
        
        // A schedule belongs to a certain loan request (relationships)
        $sched = new Schedule;
        
        // Start it now, indicating that the member's loan request has started.
        // but hte counting is starting after the first month is finished
        $sched->start_date = Carbon::now();
        $sched->end_date = $sched->start_date->copy()->addMonths($rq->days_payable + 1);
        $sched->sched_type = 2;
        $sched->user_id = $rq->user_id;
        $sched->save();

        // Save $sched first and $rq get its ID
        $rq->sched_id = $sched->id;
        $rq->save();

        return redirect()->route('admin-requests')->with('success', 'Request Accepted');
    }

    public function reject($id) 
    {
        $rq = Loan_Request::find($id);
        $rq->confirmed = false;
        $rq->paid = 1;
        $rq->save();
        return redirect()->route('admin-requests')->with('error', 'Request Rejected');
    }
}
