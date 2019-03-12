<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Loan_Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = DB::table('transactions')
                        ->join('users','users.id', '=', 'transactions.member_id')
                        ->select('*')
                        ->where('transactions.collector_id', Auth::user()->id)
                        ->orderBy('transactions.created_at', 'DESC')
                        ->paginate(10);

        return view('users.collector.dashboard')->with('transactions', $transactions)->with('active', 'dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.collector.collect')->with('active','collect');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $messages = [
            'required' => 'The :attribute field is required',
            'alpha' => 'Please use only alphabetic characters'
        ];
        $this->validate($request, [
            'amount' => ['required', 'numeric'],
            'id' => ['required', 'numeric'],
        ], $messages);

        $transact = New Transaction;
        $transact->member_id = $request->id;
        $transact->trans_type = $request->type;
        
        if($request->amount <= 49){
            return redirect()->route('transaction-collect')->with('active', 'collect')->with('error', 'Please pay above 50.00 Php');
        }

        $transact->amount = $request->amount;

        if($request->type==0){

        }else if($request->type==1){
            if(Transaction::count() == 0 || Transaction::find($request->id) == NULL){
                
                $temp = DB::table('loan_request')
                    ->where('user_id', $request->id)
                    ->where('confirmed', 1)
                    ->where('get',0)
                    ->sum('loan_amount');
            }else{
                $temp = DB::table('transactions')
                 ->join('loan_request', 'loan_request.user_id', '=', 'transactions.member_id')
                ->where('loan_request.user_id', $request->id)
                ->where('confirmed', 1)
                ->where('loan_request.get',0)
                ->where('transactions.get', 0)
                ->sum(DB::raw('transactions.balance + loan_request.loan_amount'));

                DB::table('transactions')
                ->where('member_id', $request->id)
                ->where('get',0)
                ->where('balance', '<=', 0)
                ->update(['get'=>1]);
            }

            if($temp == 0){
                $temp = DB::table('transactions')
                    ->where('member_id', $request->id)
                    ->where('get',0)
                    ->sum('transactions.balance');
                    
                    

            if($temp <= 0){
                DB::table('loan_request')
                    ->where('user_id', $request->id)
                    ->where('paid', NULL)
                    ->update(['paid'=>1]);
                    
                return redirect()->route('transaction-collect')->with('error', 'You already paid the loan');
            }

                if($temp < $request->amount){
                    $msg = 'Your payment should not above '.$temp.' Php';
                    return redirect()->route('transaction-collect')->with('error', $msg);
                }
                    
                if($temp > 0){
                    $t= DB::table('transactions')
                    ->where('member_id', $request->id)
                    ->where('get',0)
                    ->update(['get'=>1]);
                }
                
            }

            $deduct = $temp - $request->amount;
            $transact->collector_id = Auth::user()->id;
            $transact->balance = $deduct;
            $transact->get = 0;
            $transact->save();

            DB::table('loan_request')
                ->where('user_id', $request->id)
                ->where('confirmed', 1)
                ->where('get',0)
                ->update(['get' => 1]);

        }else{
            // for deposit
        }
       return redirect()->route('transaction-collect')->with('success', 'Successfully Transacted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function show(Collector $collector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function edit(Collector $collector)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collector $collector)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Collector  $collector
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collector $collector)
    {
        //
    }
}
