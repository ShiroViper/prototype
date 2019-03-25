<?php

namespace App\Http\Controllers;

use App\Loan_Request;
use App\Loan_Process;
use Illuminate\Http\Request;

// Add-on (just testing)
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Carbon\Carbon;
use App\Schedule;
use App\Status;

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
            $lr = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNotNull('confirmed')->paginate(5);
            $pending = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNull('confirmed')->paginate(5);
            // return dd($lr);  
            return view('users.admin.requests')->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        } else {
            $lr = Loan_Request::orderBy('updated_at', 'desc')->where('user_id', Auth::user()->id)->whereNotNull('confirmed')->paginate(5);
            $pending = Loan_Request::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->where('confirmed', NULL)->paginate(5);
            $unpaid = Loan_Request::where('user_id', Auth::user()->id)->whereNull('paid')->orWhere('paid', false)->first();
            
            // for transferring money to member
            $pending_mem_receive = DB::table('processes')
                ->join('loan_request', 'loan_request.id', '=', 'request_id')
                ->join('users', 'users.id', '=', 'collector_id')
                ->select('processes.id', 'transfer', 'request_id', 'collector_id', 'processes.updated_at',
                    'lname', 'fname', 'loan_amount')
                ->where('user_id', Auth::user()->id)
                ->where('transfer', 3)
                ->paginate(5);
                
            // table processes column confirmed if the member confirm the member has successfully gave the money to colletor 
            $pending_mem_con = DB::table('transactions')
                ->join('users', 'users.id', '=', 'collector_id')
                ->select('transactions.id', 'amount','lname','fname', 'mname', 'trans_type')
                ->where('confirmed', NULL)
                ->where('member_id', Auth::user()->id)
                ->paginate(5);

        //    dd($pending_mem_con, Auth::user()->id);
            return view('users.member.requests')->with('pending_mem_con',$pending_mem_con)->with('pending_mem_receive', $pending_mem_receive)->with('unpaid', $unpaid)->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $unpaid = Loan_Request::where('user_id', Auth::user()->id)->whereNull('paid')->orWhere('paid', false)->first();
        return view('users.member.loan')->with('unpaid', $unpaid)->with('active', 'loan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters'
        ];

        $this->validate($request, [
            'amount' => ['required'],
            'months' => ['required']
        ], $messages);

        // Loan * .06 = monthly interest * months payable = interest to pay + loan amount = loan to pay 
        // example: 1500 * 0.06 = 90 * 4 = 360 + 1500 = 1860
        $loan_to_pay = $request->amount * 0.06 * $request->months + $request->amount;

        $lr = new Loan_Request;
        $lr->loan_amount = $request->input('amount');
        $lr->days_payable = $request->input('months');
        $lr->balance = $loan_to_pay;
        $lr->get = 0;
        $lr->user_id = Auth::user()->id;

        // sched_id is NULL for now since it still hasn't been approved
        $lr->sched_id = null;
        // return dd($lr);
        $lr->save();

        return redirect()->action('LoanRequestsController@index');
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
        // Add 1 day from the start of the loan payment to give the members a breathing room
        $sched->start_date = Carbon::now()->addDay();
        $sched->end_date = Carbon::now()->addDays(1 + $rq->days_payable);
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
