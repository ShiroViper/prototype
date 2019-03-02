<?php

namespace App\Http\Controllers;

use App\Loan_Request;
use Illuminate\Http\Request;

// Add-on (just testing)
use App\Http\Controllers\Controller;
use Auth;
use DB;

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
            $lr = DB::table('loan_request')->join('users', 'loan_request.user_id', '=', 'users.id')->select('loan_request.*', 'users.lname', 'users.fname', 'users.mname')->orderBy('loan_request.created_at', 'desc')->whereNotNull('confirmed')->paginate(10);
            $pending = DB::table('loan_request')->join('users', 'loan_request.user_id', '=', 'users.id')->select('loan_request.*', 'users.lname', 'users.fname', 'users.mname')->orderBy('loan_request.created_at', 'desc')->whereNull('confirmed')->paginate(5);

            return view('users.admin.requests')->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        } else {
            $lr = Loan_Request::orderBy('updated_at', 'desc')->where('user_id', Auth::user()->id)->whereNotNull('confirmed')->paginate(10);
            $pending = Loan_Request::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->paginate(5);

            return view('users.member.requests')->with('requests', $lr)->with('pending', $pending)->with('active', 'requests');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.member.loan')->with('active', 'loan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required'],
            'days' => ['required']
        ]);

        $lr = new Loan_Request;
        $lr->loan_amount = $request->input('amount');
        $lr->days_payable = $request->input('days');
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
    public function show(Loan_Request $loan_Request)
    {
        //
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
        return redirect()->route('admin-requests')->with('success', 'Request Accepted');
    }

    public function reject($id) {
        $rq = Loan_Request::find($id);
        $rq->confirmed = false;
        $rq->save();
        return redirect()->route('admin-requests')->with('error', 'Request Rejected');
    }
}
