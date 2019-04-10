<?php

namespace App\Http\Controllers;

use App\Status;
use App\Loan_Request;
use App\Transaction;
use App\Process;
use App\TurnOver;
use Auth;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (Auth::user()->user_type){
            case 0:
            // This will fetch the savings, patronage refund and distribution for member status
                $savings = Status::where('user_id', Auth::user()->id)->first();
                $patronage = Status::where('user_id', Auth::user()->id)->first();
                $loan = Loan_Request::where([['user_id',Auth::user()->id], ['confirmed', 1], ['received',1], ['paid', NULL]])->first();

                return view('users.member.status')->with('loan', $loan)->with('savings', $savings)->with('patronage', $patronage)->with('active', 'status');
            break;

            case 1:
                // fetch all transactions
                $trans = Transaction::where([['collector_id', Auth::user()->id], ['confirmed', 1]])->get();
                // ->whereraw('date(created_at) = date(now())')
                // fetch all processes and display in table
                $process = Process::select('lname', 'fname', 'mname', 'loan_amount')->join('loan_request', 'loan_request.id', 'request_id')->join('users', 'users.id', '=', 'user_id')->where([['transfer','>=', 2], ['transfer','<=', 3], ['collector_id', Auth::user()->id]])->paginate(5);
                // check if collector already request for money turn over
                $turn_over = TurnOver::where([['collector_id', Auth::user()->id], ['confirmed', null]])->first();
                /* This for finding duplicate token in table */
                $token = Str::random(10);
                $check_token = TurnOver::select('token')->get();
                for ($x = 0; $x < count($check_token); $x++){
                    if($check_token[$x]->token == $token){
                        $token = Str::random(10);
                    }
                }
                // ================================================
                // add amount for deposit and loan payment
                $deposit = 0;
                $loan_payment = 0;
                foreach($trans as $a){
                    if($a->trans_type == 1 AND $a->turn_over != 2){
                        $deposit = $deposit + $a->amount; 
                    }else if($a->trans_type == 3 AND $a->turn_over != 2){
                        $loan_payment = $loan_payment + $a->amount;
                    }
                }
                // count
                $count = count($trans) - 1;

                return view('users.collector.status')->with('count', $count)->with('turn_over', $turn_over)->with('token', $token)->with('process', $process)->with('deposit', $deposit)->with('loan_payment', $loan_payment)->with('active', 'status')->with('trans', $trans);
            break;

            case 2:
               
            break;
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
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        //
    }

    public function accept($id){
        $turn_over = TurnOver::where('id', $id)->first();
        $trans = Transaction::where([['collector_id', $turn_over->collector_id], ['confirmed', 1], ['turn_over', 1]])->update(array('turn_over'=> 2));
        $turn_over->confirmed = 1;
        $turn_over->save();

        return redirect()->back()->with('success', 'Confirmed');
    }

    public function transfer($token){
        $trans = Transaction::where([['collector_id', Auth::user()->id], ['confirmed', 1], ['turn_over', null]])->get();
        $turn_over = TurnOver::where([['collector_id', Auth::user()->id], ['confirmed', null]])->first();

        $amount = 0;
        $i = 0;
        foreach($trans as $t){
            $amount = $amount + $t->amount;
            $i ++;
        }

        if(!$turn_over){
            $turn_over = New TurnOver;
            $turn_over->collector_id = Auth::user()->id;
            $turn_over->amount = $amount;
            $turn_over->token = $token;
            $turn_over->save();
        }        
        // update turn_over to 1 = pending
        Transaction::where([['collector_id', Auth::user()->id], ['confirmed', 1], ['turn_over', null]])->update(array('turn_over' => 1));

        return redirect()->back()->with('success', 'Waiting confirmation from the admin');
    }
}
