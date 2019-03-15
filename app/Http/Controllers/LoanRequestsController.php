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
            // $lr = DB::table('loan_request')->join('users', 'loan_request.user_id', '=', 'users.id')->select('loan_request.*', 'users.lname', 'users.fname', 'users.mname')->orderBy('loan_request.created_at', 'desc')->whereNotNull('confirmed')->paginate(10);
            // $pending = DB::table('loan_request')->join('users', 'loan_request.user_id', '=', 'users.id')->select('loan_request.*', 'users.lname', 'users.fname', 'users.mname')->orderBy('loan_request.created_at', 'desc')->whereNull('confirmed')->paginate(5);

            $lr = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNotNull('confirmed')->paginate(10);
            $pending = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNull('confirmed')->paginate(5);
            
            // For Money Transferred To collector table
            $transferred = DB::table('loan_process')
                ->select('loan_process.updated_at','request_id', 'lname', 'fname', 'collector_id', 'loan_amount', 'transfer')
                ->join('users', 'users.id', '=', 'collector_id')
                ->join('loan_request','loan_request.id', '=', 'request_id')
                ->where('transfer','>=', 2)
                ->paginate(5);
            
            // return dd($lr);

            return view('users.admin.requests')->with('transferred', $transferred)->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        } else {
            $lr = Loan_Request::orderBy('updated_at', 'desc')->where('user_id', Auth::user()->id)->whereNotNull('confirmed')->paginate(10);
            $pending = Loan_Request::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->where('confirmed', NULL)->paginate(5);
            $unpaid = Loan_Request::where('user_id', Auth::user()->id)->whereNull('paid')->orWhere('paid', false)->first();
            
            // for transferring money to member
            $transferring = DB::table('loan_process')
                ->join('loan_request', 'loan_request.id', '=', 'request_id')
                ->join('users', 'users.id', '=', 'collector_id')
                ->select('loan_process.id', 'transfer', 'request_id', 'collector_id', 'loan_process.updated_at',
                    'lname', 'fname', 'loan_amount')
                ->where('user_id', Auth::user()->id)
                ->where('transfer', 3)
                ->paginate(5);
                
            
            // return dd($transferring);
           
            return view('users.member.requests')->with('transferring',$transferring)->with('transferring', $transferring)->with('unpaid', $unpaid)->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
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
        // return dd($request->input());
        $this->validate($request, [
            'amount' => ['required'],
            'days' => ['required']
        ]);

        $lr = new Loan_Request;
        $lr->loan_amount = $request->input('amount');
        $lr->days_payable = $request->input('days');
        $lr->get = 0;
        $lr->user_id = Auth::user()->id;
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
        $rq->delete();
        return redirect()->route('member-requests')->with('success', 'Request removed successfully');
    }

    public function accept($id) {
        $rq = Loan_Request::find($id);
        $rq->confirmed = true;
        $rq->save();
        
        $sched = new Schedule;
        // A schedule belongs to a certain loan request (relationships)
        $sched->loanRequest()->associate($rq);
        $sched->loan_request_id = $sched->loanRequest->id;
        $sched->start_date = Carbon::now();
        $sched->end_date = Carbon::now()->addDays($sched->loanRequest->days_payable);
        $sched->save();

        return redirect()->route('admin-requests')->with('success', 'Request Accepted');
    }

    public function reject($id) {
        $rq = Loan_Request::find($id);
        $rq->confirmed = false;
        $rq->paid = 1;
        $rq->save();
        return redirect()->route('admin-requests')->with('error', 'Request Rejected');
    }
}
