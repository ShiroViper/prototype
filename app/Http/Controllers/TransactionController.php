<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Loan_Request;
use App\Schedule;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report(){

        $reports = DB::table('transactions')
                        ->select('loan_request.created_at',DB::raw('ADDDATE(loan_request.created_at, 31) AS due_date') ,'days_payable', 'member_id', 'users.lname', 'users.fname', 'collector.lname as col_lname', 'collector.fname as col_fname', 'balance' )
                        ->join('loan_request','user_id', '=', 'member_id')
                        ->join('users', 'users.id', '=', 'member_id')
                        ->join('users as collector', 'collector.id', '=', 'collector_id')
                        ->whereIn('transactions.id', function($query){
                            $query->select(DB::raw('MAX(transactions.id)'))
                            ->from('transactions')
                            ->groupby('member_id');
                        })
                        ->orderBy('transactions.created_at', 'desc')
                        ->paginate(10);

                        $now = date("M d Y",strtotime('2019-07-13 10:59:44'));
                        // $now = date("M d Y",strtotime(NOW()));
                        $past = date("M d Y", strtotime($reports[0]->due_date));
                        // return $now. "<br>". $past;
                        $try = "2019-07-13 10:59:44";

                        if($try < $reports[0]->due_date){
                            return NOW(). " is more than ".$reports[0]->due_date;
                        }
                        
                        return dd($past);

                        // $stop_date = NOW();
                        // $stop_date = date('Y-m-d', strtotime($stop_date . ' +30 day'));
                        // return dd ($stop_date);

        return view('users.collector.reports')->with('reports', $reports)->with('active', 'reports');
    }
    public function index()
    {
        if ( Auth::user()->user_type == 2 ) {
            $transactions = Transaction::orderBy('id', 'desc')->paginate(10);
            return view('users.admin.dashboard')->with('transactions', $transactions)->with('active', 'transactions');
        } else if ( Auth::user()->user_type == 1 ) {
            // return dd($transactions);
            $transactions = Transaction::where('collector_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            return view('users.collector.dashboard')->with('transactions', $transactions)->with('active', 'dashboard');
        } else {
            $transactions = Transaction::where('member_id', '=', Auth::user()->id)->paginate(10);
            return view('users.member.transactions')->with('transactions', $transactions)->with('active', 'transactions');
        }
        // $transactions = DB::table('transactions')
        //                 ->join('users','users.id', '=', 'transactions.member_id')
        //                 ->select('*')
        //                 ->where('transactions.collector_id', Auth::user()->id)
        //                 ->orderBy('transactions.created_at', 'DESC')
        //                 ->paginate(10);

        // return view('users.collector.dashboard')->with('transactions', $transactions)->with('active', 'dashboard');
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
            'required' => 'This field is required',
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
            return redirect()->route('transaction-collect')->with('active', 'collect')->with('error', 'Please pay above 50.00 Php')->withInput();
        }

        $transact->amount = $request->amount;

    // ***************************      Start           ***************************
         if ($transact->trans_type == 1) { 
            // If the transaction is a Deposit
            return dd('Deposittt');
        } 
        
        else if ($transact->trans_type == 2) {
            // If the transaction is a Loan
            return dd('Loan');
        }
        
        else {
            // If the transaction is a Loan Payment [ $trans_type = 3 ]
            if (Transaction::where('member_id', '=', $transact->member_id)->first() == NULL) {
                // If this is the first transaction made by the member
                $loan_request = Loan_Request::where([
                    ['user_id', '=', $transact->member_id],
                    ['confirmed', '=', 1],
                    ['get', '=', 0]
                ])->first();        // Get the loan_request
                $loan_request->balance = $loan_request->balance - $transact->amount;
                $loan_request->get = 1;
                $loan_request->save();
            } else {
                // return dd('2nd transaction made by the user');
                $loan_request = Loan_Request::where([
                    ['user_id', '=', $transact->member_id],
                    ['confirmed', '=', 1],
                    ['paid', '=', null]
                ])->first();        // Get the latest update of the loan_request
                // return dd($loan_request, 'No more payments needed');
                if (is_null($loan_request)) {
                    // There are no more loan payments by the user
                    return redirect()->route('transaction-collect')->with('error', "User doesn't have any payments");
                } else if (($loan_request->balance - $transact->amount) < 0) {
                    // Prevents the collector to input the amount that is larger than the balance
                    return redirect()->route('transaction-collect')->with('error', 'Amount entered is beyond the balance')->withInput();
                } else if (($loan_request->balance - $transact->amount) == 0) {
                    $loan_request->balance = $loan_request->balance - $transact->amount;
                    $loan_request->paid = 1;    // Payment is closed/done
                } else {
                    // Payment continues
                    $loan_request->balance = $loan_request->balance - $transact->amount;
                }
                $loan_request->save();
            }
        }     

        $transact->get = 1;     // Serves as the basis for the next transaction?
        $transact->collector_id = Auth::user()->id;
        $transact->save();

        // Create a paid date
        $sched = new Schedule;
        $sched->user_id = $transact->member_id;
        $sched->sched_type = 3;     // [3] Paid Loan Schedule (See SchedulesController)
        $sched->start_date = Carbon::now()->format('Y-m-d');
        $sched->end_date = Carbon::now()->format('Y-m-d');
        $sched->save();

        return redirect()->route('transaction-collect')->with('success', 'Transaction Successful');

    // ***************************      End             ***************************
    // ----------------------------------------------------------------------------



    // ***************************      NIKE's CODE     ***************************

    //     if($request->type==0){
    //     } else if($request->type==1) {
    //         if( Transaction::find($request->id) == NULL){
    //             $temp = DB::table('loan_request')
    //                 ->where('user_id', $request->id)
    //                 ->where('confirmed', 1)
    //                 ->where('get',0)
    //                 ->sum('loan_amount');
    //         } else {
    //             $temp = DB::table('transactions')
    //              ->join('loan_request', 'loan_request.user_id', '=', 'transactions.member_id')
    //             ->where('loan_request.user_id', $request->id)
    //             ->where('confirmed', 1)
    //             ->where('loan_request.get',0)
    //             ->where('transactions.get', 0)
    //             ->sum(DB::raw('transactions.balance + loan_request.loan_amount'));
    //         }

    //         if($temp == 0){
    //             $temp = DB::table('transactions')
    //                 ->where('member_id', $request->id)
    //                 ->where('get',0)
    //                 ->sum('transactions.balance');
                
    //         if(!Transaction::find($request->id)){
    //             return redirect()->route('transaction-collect')->with('error', 'Member ID: '.$request->id. ' Not Found');
    //         }
    //         if($temp <= 0){                    
    //             return redirect()->route('transaction-collect')->with('error', 'You already paid the loan');
    //         }
            

    //         DB::table('transactions')
    //         ->where('member_id', $request->id)
    //         ->where('get',0)
    //         ->where('balance', '<=', 0)
    //         ->update(['get'=>1]);

    //             if($temp < $request->amount){
    //                 $msg = 'Your payment should not above '.$temp.' Php';
    //                 return redirect()->route('transaction-collect')->with('error', $msg);
    //             }
                    
    //             if($temp > 0){
    //                 $t= DB::table('transactions')
    //                 ->where('member_id', $request->id)
    //                 ->where('get',0)
    //                 ->update(['get'=>1]);
    //             }
                
    //         }

           

    //         $deduct = $temp - $request->amount;
    //         if($deduct == 0){
    //             DB::table('loan_request')
    //                 ->where('user_id', $request->id)
    //                 ->where('paid', NULL)
    //                 ->update(['paid'=>1]);
    //         }
    //         $transact->collector_id = Auth::user()->id;
    //         $transact->balance = $deduct;
    //         $transact->get = 0;
    //         $transact->save();

    //         DB::table('loan_request')
    //             ->where('user_id', $request->id)
    //             ->where('confirmed', 1)
    //             ->where('get',0)
    //             ->update(['get' => 1]);

    //     }else{
    //         // for deposit
    //     }
    //    return redirect()->route('transaction-collect')->with('success', 'Successfully Transacted');


    // ***************************          END         ***************************
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
