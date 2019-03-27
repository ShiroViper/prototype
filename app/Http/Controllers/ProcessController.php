<?php

namespace App\Http\Controllers;

use App\Process;
use App\User;
use App\Loan_Request;
use Illuminate\Http\Request;
use Auth;
use DB;

class ProcessController extends Controller
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
        // $pending = Process::where('collector_id', '=', Auth::user()->id)->paginate(10);
        $pending_col = DB::table('processes')
            ->join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'admin_id')
            ->select('processes.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'processes.updated_at','lname', 'fname', 'loan_amount', 'days_payable')
            ->where('collector_id', Auth::user()->id)->where('transfer', 1)
            ->paginate(5);

        $received_col = DB::table('processes')
            ->join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'admin_id')
            ->select('processes.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'processes.updated_at','lname', 'fname', 'loan_amount', 'days_payable')
            ->where('collector_id', Auth::user()->id)->where('transfer', '>=', 2)->where('transfer', 2)
            ->paginate(5);
            
        $transferred_mem = DB::table('processes')
            ->join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'user_id')
            ->select('processes.id', 'transfer', 'request_id', 'user_id', 'collector_id', 'processes.updated_at','lname', 'fname', 'loan_amount', 'days_payable')
            ->where('collector_id', Auth::user()->id)->where('transfer', 4)
            ->paginate(5);

        $confirmed = DB::table('transactions')
            ->join('users', 'users.id', '=', 'member_id')
            ->select('transactions.id', 'amount', 'lname', 'fname','mname')
            ->where('confirmed', null)
            ->paginate(5);
            // dd($received);

        return view('users.collector.requests')->with('confirmed', $confirmed)->with('transferred_mem', $transferred_mem)->with('received_col', $received_col)->with('pending_col', $pending_col)->with('active', 'request');
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
        $messages = [
            'required' => 'This field is required',
            'id' => 'Must be a valid ID Number'
        ];
        $this->validate($request, [
            'id' => ['required', 'numeric'],
        ], $messages);

        // this check if the inputted collector id is found or not else redirect and throw an error;
        $check = User::where([['id', $request->id], ['user_type', 1]])->first();
        if ($check){
            // create a new row that the money is being transfer to collector
            $process = New Process;
            $process->transfer = 1;
            $process->request_id = $request->request_id;
            $process->admin_id = Auth::user()->id;
            $process->collector_id = $check->id;
            $process->save();
            return redirect()->route('admin-requests')->with('success', 'Wating to Accept Collector: '.$check->lname. ' '.$check->fname);
        }else{
            
            return redirect()->back()->withInput()->with('error', 'Collector: Not found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Process  $Process
     * @return \Illuminate\Http\Response
     */
    public function show(Process $Process)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Process  $Process
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // find any request in loan_request and any Process table based on request id
        $process = Loan_Request::find($id);
        $trans = Process::where('request_id', '=', $id)->first();
        $user = User::where('id', '=', $process->user_id)->first();
        // NIKEEEEEEE!!! AHHAHHAAHAH
        // $user = User::where('user_type', '=', 1)->first();

        // This function fix $trans not defined
        if($trans == NULL ){
            $trans = NULL;
        }   

        // Gets collector info
        $collectors = User::where('user_type', '=', 1)->select('id','lname','fname','mname')->orderBy('id', 'desc')->get();

        // return dd($user);

        return view ('users.admin.process')->with('user', $user)->with('trans',$trans)->with('active', 'requests')->with('process',$process)->with('collectors', $collectors);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Process  $Process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Process $Process)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Process  $Process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Process $Process)
    {
        //
    }

    public function accept($id) {
        $process = Process::where('request_id', '=', $id)->first();
        // dd($process);

        if($process->transfer ==1 ){
            $process->transfer = 2;
            $process->save();
            return redirect()->route('collector-requests')->with('success', 'Money Accepted');
        }
        // This will check first if transfer == 2 otherwise skip this and create new Process 
        // execute this function if the money is being transfer from collector to member
        if($process->transfer == 2){
            $process->transfer = 3;
            $process->save();
            return redirect()->route('collector-requests')->with('success', 'Waiting to Accept Member');
        }

        if ($process->transfer == 3){
            $received = Loan_Request::where('id', $id)->first();
            $received->received = 1;
            $process->transfer = 4;
            $received->save();
            $process->save();
            return redirect()->route('member-requests')->with('success', 'Money Accepted');
        }
    }
}
