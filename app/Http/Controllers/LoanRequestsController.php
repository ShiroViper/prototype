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

            // $lr = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNotNull('confirmed')->paginate(5);
            // dd ($lr);
            $pending = Loan_Request::orderBy('loan_request.created_at', 'desc')->whereNull('confirmed')->paginate(5);

            // This code use for transfer button in request history:
            $lr = DB::table('loan_request')
                ->join('loan_process', 'loan_process.request_id', '=', 'loan_request.id')
                ->join('users', 'users.id', '=', 'user_id')
                ->select('loan_request.updated_at', 'lname', 'fname', 'mname', 'loan_amount', 'days_payable', 'confirmed', 'received', 'paid', 'loan_request.id', 'transfer')
                ->paginate(5);

            return view('users.admin.requests')->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        } else {
            $lr = Loan_Request::orderBy('updated_at', 'desc')->where('user_id', Auth::user()->id)->whereNotNull('confirmed')->paginate(5);
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
                
            
            // return dd($pending);
           
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
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters'
        ];

        $this->validate($request, [
            'amount' => ['required'],
            'days' => ['required']
        ], $messages);

        $lr = new Loan_Request;
        //  Store the Computed Compound Interest  ex: 6% 
        $lr->loan_amount = $request->input('amount') * 0.94;
        $lr->days_payable = $request->input('days');
        $lr->get = 0;
        $lr->user_id = Auth::user()->id;

        // Compute for Compound Interest
        $lr->balance = $request->input('amount')*1.06;

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
        $rq->delete();
        return redirect()->route('member-requests')->with('success', 'Request removed successfully');
    }

    public function accept($id) 
    {
        $rq = Loan_Request::find($id);
        $rq->confirmed = true;
        
        // A schedule belongs to a certain loan request (relationships)
        $sched = new Schedule;
        $sched->start_date = Carbon::now();
        $sched->end_date = Carbon::now()->addDays($rq->days_payable);
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
