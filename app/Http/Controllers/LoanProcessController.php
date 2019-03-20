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
    /* 
    * Transfer == 1: Money transferring to collector
    *             2: Money successfully transferred to collector
    *             3: Money transferring to Member
    *             4: Money successfully transferred to member
    */
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
        // This will check first if transfer == 2 otherwise create new loan_process row
        // execute this function if the money is being transfer from collector to member
        if($request->transfer == 2){
            $check = User::find($request->m_id);
            $process = Loan_Process::where('request_id', '=', $request->id)->first();
            $process->transfer = 3;
            $process->save();
            return redirect()->route('collector-requests')->with('success', 'Waiting to Accept Member: '.$request->m_id);
        }

        
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters'
        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'alpha'],
        ], $messages);
        // this check if the inputted collector name is found or not else redirect and throw an error;
        // name or username?
        $check = User::where('lname',$request->name)->where('user_type',1)->first();
        if (!$check){
            return redirect()->back()->with('error', 'Collector: '. $request->name. ' Not found');
        }
        // create a new row that the money is being transfer to collector
        $process = New Loan_Process;
        $process->transfer = 1;
        $process->request_id = $request->id;
        $process->admin_id = Auth::user()->id;
        $process->collector_id = $check->id;
        $process->save();
        return redirect()->route('admin-requests')->with('success', 'Wating to Accept Collector: '.$check->lname. ' '.$check->fname);
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
        // find any request in loan_request and any Loan_process table based on request id
        $process = Loan_Request::find($id);
        $trans = Loan_Process::where('request_id', '=', $id)->first();
        $user = User::where('id', $process->user_id)->first();

        // This function fix $trans not defined
        if($trans == NULL ){
            $trans = NULL;
        }   

        return view ('users.admin.process')->with('user', $user)->with('trans',$trans)->with('active', 'requests')->with('process',$process);
    }
    
    // Collector Loan Process 
    public function col_edit($id)
    {
        // This code Check if $id has any data in Loan_process use for updating transfer column
        $process = Loan_Process::where('id',$id)->first();
        if($process != NULL){
            $request = Loan_Request::where('id', '=', $process->request_id)->first();
            $user = User::where('id', $request->user_id)->first();
        }else{
            $process = NULL;
        }   
        return view ('users.collector.process')->with('user', $user)->with('request',$request)->with('active', 'request')->with('process',$process);
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
