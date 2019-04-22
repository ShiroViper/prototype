<?php

namespace App\Http\Controllers;

use App\Process;
use App\User;
use App\Loan_Request;
use App\Transaction;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
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
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Process::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================
        
        $pending_col = Process::join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'admin_id')->select('processes.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'processes.updated_at','lname', 'fname', 'loan_amount', 'days_payable')->where([['collector_id', Auth::user()->id],['transfer',1]])->paginate(5);
        $received_col = Process::join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'user_id')->select('processes.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'processes.updated_at', 'lname', 'fname', 'mname', 'loan_amount', 'days_payable')->where([['collector_id', Auth::user()->id], ['transfer', 2]])->paginate(5);
        $transferred_mem = Process::join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'user_id')->select('processes.id', 'transfer', 'request_id', 'user_id', 'collector_id', 'processes.updated_at','lname', 'fname', 'mname', 'loan_amount', 'days_payable')->where([['collector_id', Auth::user()->id],['transfer',4]])->paginate(5);
        $confirmed = Transaction::join('users', 'users.id', '=', 'member_id')->select('transactions.id', 'amount', 'lname', 'fname','mname')->where('confirmed', null)->paginate(5);
        $pending_to_mem = Process::join('loan_request', 'loan_request.id', '=', 'request_id')->join('users', 'users.id', '=', 'user_id')->select('processes.id', 'transfer', 'request_id', 'admin_id', 'collector_id', 'processes.updated_at', 'lname', 'fname', 'mname', 'loan_amount', 'days_payable')->where([['collector_id', Auth::user()->id], ['transfer', 3]])->paginate(5);
        // dd($pending_to_mem);
        return view('users.collector.requests')->with('token', $token)->with('pending_to_mem', $pending_to_mem)->with('confirmed', $confirmed)->with('transferred_mem', $transferred_mem)->with('received_col', $received_col)->with('pending_col', $pending_col)->with('active', 'request');
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
        // This check if the token is duplicate or not after the form is saved, tricks the user that he/she store only one form. (several clicks in the button  )
        if(Process::where('token', $request->token)->first()){
            return redirect()->action('Loan_RequestController@index');
        }

        $messages = [
            'required' => 'This field is required',
            // 'id' => 'Must be a valid ID Number'
            'colID' => 'Account ID not found!'
        ];
        $this->validate($request, [
            'colName' => ['required'],
            'colID' => ['required']
        ], $messages);



        // this check if the inputted collector id is found or not else redirect and throw an error;
        $check = User::where([['id', $request->colID], ['user_type', 1]])->first();
        if ($check){
            // create a new row that the money is being transfer to collector
            $process = New Process;
            $process->transfer = 1;
            $process->request_id = $request->request_id;
            $process->admin_id = Auth::user()->id;
            $process->collector_id = $check->id;
            $process->token = $request->token;
            $process->save();
            return redirect()->back()->with('success', 'Wating to Accept Collector: '.$check->lname. ' '.$check->fname);
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
        
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Process::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================

        return view ('users.admin.process')->with('token', $token)->with('user', $user)->with('trans',$trans)->with('active', 'requests')->with('process',$process)->with('collectors', $collectors);

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

    public function accept($id, $token) {
        $process = Process::where('request_id', '=', $id)->first();
        if($check = Process::where('token', $token)->first()){
            if($check->transfer == 1){
                return redirect()->back()->with('success', 'Money Accepted');
            }else if($check->transfer == 2){
                return redirect()->back()->with('success', 'Waiting to Accept Member');
            }else if($check->transfer == 3){
                return redirect()->back()->with('success', 'Money Accepted');
            }
        }else if($process->transfer ==1 ){
            $process->transfer = 2;
            $process->token = $token;
            $process->save();
            return redirect()->route('collector-requests')->with('success', 'Money Accepted');

        // This will check first if transfer == 2 otherwise skip this and create new Process 
        // execute this function if the money is being transfer from collector to member
        }else if($process->transfer == 2){
            $process->transfer = 3;
            $process->token = $token;
            $process->save();
            return redirect()->route('collector-requests')->with('success', 'Waiting to Accept Member');
        }else if ($process->transfer == 3){
            $received = Loan_Request::where('id', $id)->first();
            $received->received = 1;
            $received->per_month_from = strtotime(NOW());
            $received->per_month_to = strtotime(NOW().'+ 1 months');
            $received->date_approved = strtotime(NOW());
            // dd(date('F d, Y', $received->per_month_updated_at), $received->per_month_updated_at);
            $process->transfer = 4;
            $process->token = $token;
            $received->save();
            $process->save();
            return redirect()->route('member-requests')->with('success', 'Money Accepted');
        }
    }
}
