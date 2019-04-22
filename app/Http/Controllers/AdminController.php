<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Status;
use App\Distribution;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function failed(){
        $failures = DB::table('transactions')
            ->select(DB::raw('ADDDATE(loan_request.created_at, days_payable) AS due_date') , 'member_id', 'users.lname', 'users.fname', 'collector.lname as col_lname', 'collector.fname as col_fname', 'balance' )
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
            // return dd($deliquents);
            // No Values if transactions table empty

        return view('users.collector.failed-deposit')->with('failures', $failures)->with('active', 'failed');
    }
    public function deliquent(){

        $deliquents = DB::table('transactions')
            ->select(DB::raw('ADDDATE(loan_request.created_at, 30) AS due_date') , 'member_id', 'users.lname', 'users.fname', 'collector.lname as col_lname', 'collector.fname as col_fname', 'balance' )
            ->join('loan_request','user_id', '=', 'member_id')
            ->join('users', 'users.id', '=', 'member_id')
            ->join('users as collector', 'collector.id', '=', 'collector_id')
            ->whereIn('transactions.id', function($query){
                $query->select(DB::raw('MAX(transactions.id)'))
                ->from('transactions')
                ->groupby('member_id');
            })
            ->where('collector_id', Auth::user()->id)
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);
            
            // No Values if transactions table empty

        return view('users.collector.deliquent')->with('deliquents', $deliquents)->with('active', 'deliquents');
    }

    public function distribute(){
        $status = Status::where('user_id', 1)->first();
        // $user = User::where([['user_type', 0], ['setup', 1], ['inactive', '!=', 1]])->get();
        $user = User::join('status', 'status.user_id', '=', 'users.id')->select('users.*')->where([['user_type', 0], ['setup', 1], ['inactive', '!=', 1],['savings', '>=', 1825]])->get();
        $user_count = count($user);

        if($status->distribution <= 0 && date('n', strtotime(NOW())) > 3){
            return redirect()->back()->with('error', 'Action Invalid! Distribution is done');
        }else if($status->distribution > 0 && date('n', strtotime(NOW())) > 3){
            
            $distribute = $status->distribution / $user_count;
            foreach($user as $u){
                // dd(! $temp = Distribution::where([['user_id', $u->id], ['confirmed', null]])->first(), $temp);
                if(!Distribution::where([['user_id', $u->id], ['confirmed', null], [DB::raw('ADDDATE(created_at, INTERVAL 1 month)'), '>', NOW()]])->first() ){
                    if(!Distribution::where([['user_id', $u->id], ['confirmed', 1], [DB::raw('ADDDATE(created_at, INTERVAL 1 month)'), '>', NOW()]])->first()){
                        $dis = New Distribution;
                        $dis->user_id = $u->id;
                        $dis->amount = $distribute;
                        $dis->save();
                        
                        // save this in confirmation
                        $status->save();
                    }
                }
            }
            return redirect()->back()->with('success', 'Pending confirmation from member');
        }
    }

    public function index()
    {
        // $user = User::find(Auth::user()->id);
        // return view('users.admin.dashboard')->with('active','dashboard')->with('user', $user);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
