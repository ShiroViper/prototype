<?php

namespace App\Http\Controllers;

use App\Loan_Process;
use App\User;
use App\Loan_Request;
use DB;
use Illuminate\Http\Request;
use Auth;

class LoanProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $pending = Loan_Process::where('collector_id', '=', Auth::user()->id)->paginate(10);
        $pending = DB::table('loan_process')
            ->join('loan_request', 'loan_request.id', '=', 'request_id')
            ->join('users', 'users.id', '=', 'admin_id')
            ->select('loan_process.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'loan_process.updated_at',
                'lname', 'fname', 'loan_amount', 'days_payable')
            ->where('collector_id', Auth::user()->id)
            ->where('transfer', 1)
            ->paginate(5);

        $received = DB::table('loan_process')
            ->join('loan_request', 'loan_request.id', '=', 'request_id')
            ->join('users', 'users.id', '=', 'admin_id')
            ->select('loan_process.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'loan_process.updated_at',
                'lname', 'fname', 'loan_amount', 'days_payable')
            ->where('collector_id', Auth::user()->id)
            ->where('transfer', '>=', 2)
            ->where('transfer', '<', 4)
            ->paginate(5);
            
        $transferred = DB::table('loan_process')
            ->join('loan_request', 'loan_request.id', '=', 'request_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->select('loan_process.id', 'transfer', 'request_id', 'user_id', 'collector_id', 'loan_process.updated_at',
                'lname', 'fname', 'loan_amount', 'days_payable')
            ->where('collector_id', Auth::user()->id)
            ->where('transfer', 4)
            ->paginate(5);

        
        return view('users.collector.requests')->with('transferred', $transferred)->with('received', $received)->with('pending', $pending)->with('active', 'request');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.admin.process')->with('active', 'process');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->transfer == 2){
            $check = User::find($request->m_id);
            $process = Loan_Process::where('request_id', '=', $request->id)->first();
            $process->transfer = 3;
            $process->save();
            return redirect()->route('collector-requests')->with('success', 'Waiting to Accept Member ID: '.$request->m_id);
        }
        // this check if the inputted id is found or not else redirect and throw an error;
        $check = User::where('id',$request->c_id)->where('user_type',1)->first();
        if ($check){
            $process = New Loan_Process;
            $process->transfer = 1;
            $process->request_id = $request->id;
            $process->admin_id = Auth::user()->id;
            $process->collector_id = $request->c_id;
            $process->save();
        }else{
            return redirect()->back()->with('error', 'Collector ID: '. $request->c_id . ' Not found');
        }
        return redirect()->route('admin-requests')->with('success', 'Wating to Accept Collector ID: '.$request->c_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loan_Process  $loan_Process
     * @return \Illuminate\Http\Response
     */
    public function show(Loan_Process $loan_Process)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan_Process  $loan_Process
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $process = Loan_Request::find($id);
        $trans = Loan_Process::where('request_id', '=', $id)->first();
        if($trans == NULL ){
            $trans = NULL;
        }   
        // return dd($id);

        return view ('users.admin.process')->with('trans',$trans)->with('active', 'requests')->with('process',$process);
    }
    
    public function col_edit($id)
    {
        $process = Loan_Process::where('id',$id)->first();
        if($process != NULL){
            $request = Loan_Request::where('id', '=', $process->request_id)->first();
        }else{
            $process = NULL;
        }

        // return dd ($process);

        return view ('users.collector.process')->with('request',$request)->with('active', 'request')->with('process',$process);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan_Process  $loan_Process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan_Process $loan_Process)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan_Process  $loan_Process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan_Process $loan_Process)
    {
        //
    }

    public function accept($id) {
        $process = Loan_Process::where('request_id', '=', $id)->first();
        if($process){
            $process->transfer += 1;
            $process->save();
        }
        if ($process->transfer == 4){
            $received = Loan_Request::where('id', $id)->first();
            $received->received = 1;
            $received->save();
            return redirect()->route('member-requests')->with('success', 'Request Accepted');
        }

        return redirect()->route('collector-requests')->with('success', 'Money Accepted');
    }

}
