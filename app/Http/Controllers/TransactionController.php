<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use App\Status;
use App\Loan_Request;
use App\User;
use App\Schedule;
use App\Transaction;
use App\Member_Request;
use App\TurnOver;
use App\Distribution;
use DateTime;
use Illuminate\Support\Facades\Crypt;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function failed(){
        $failures = Transaction::select(DB::raw('ADDDATE(loan_request.created_at, days_payable) AS due_date') , 'member_id', 'users.lname', 'users.fname', 'collector.lname as col_lname', 'collector.fname as col_fname', 'balance' )->join('loan_request','user_id', '=', 'member_id')->join('users', 'users.id', '=', 'member_id')->join('users as collector', 'collector.id', '=', 'collector_id')
            ->whereIn('transactions.id', function($query){
                $query->select(DB::raw('MAX(transactions.id)'))
                ->from('transactions')
                ->groupby('member_id');
            })->where(DB::raw('ADDDATE(loan_request.created_at, days_payable)'), '<', NOW())->orderBy('transactions.created_at', 'desc')->paginate(5);
            // No Values if transactions table empty
        return view('users.admin.failed-deposit')->with('failures', $failures)->with('active', 'failed');
    }
    public function deliquent(){

        $deliquents = Transaction::select(DB::raw('ADDDATE(transactions.created_at, 30) AS due_date') , 'member_id', 'users.lname', 'users.fname', 'users.mname', 'collector.lname as col_lname', 'collector.fname as col_fname', 'collector.mname as col_mname', 'balance' )->join('loan_request','user_id', '=', 'member_id')->join('users', 'users.id', '=', 'member_id')->join('users as collector', 'collector.id', '=', 'collector_id')
            ->whereIn('transactions.id', function($query){
                $query->select(DB::raw('MAX(transactions.id)'))
                ->from('transactions')
                ->groupby('member_id');
            })->where(DB::raw('ADDDATE(transactions.created_at, 30)'), '<', NOW())->orderBy('transactions.created_at', 'desc')->paginate(5);
            // dd($deliquents);
            // No Values if transactions table empty
        return view('users.admin.deliquent')->with('deliquents', $deliquents)->with('active', 'deliquent');
    }
    public function index()
    {
        if ( Auth::user()->user_type == 2 ) {
            // Pending Loan Requests
            $pending = Loan_Request::join('comments', 'request_id', '=', 'loan_request.id')->join('users', 'users.id', '=', 'loan_request.user_id')->select( 'request_id', 'lname', 'mname', 'fname', 'loan_request.created_at', 'loan_amount', 'days_payable', 'comments')->orderBy('loan_request.created_at', 'desc')->whereNull('loan_request.confirmed')->paginate(5);

            // Member Requests
            $memberRequests = Member_Request::where('approved', null)->paginate(5);

            // Transactions
            $transactions = Transaction::join('users', 'users.id', '=' ,'member_id')->select('transactions.id', 'trans_type', 'transactions.created_at', 'lname', 'fname', 'mname', 'amount')->where('confirmed',1)->orderBy('transactions.created_at', 'desc')->paginate(10);

            $trans = Transaction::where([['confirmed', 1], ['turn_over', 2]])->get();
            $turn_over = TurnOver::select('turn_over.id', 'lname', 'fname', 'mname', 'amount')->join('users', 'users.id', '=', 'collector_id')->where('confirmed', null)->paginate(5);
            $status = Status::select(DB::raw('SUM(savings) as savings, SUM(patronage_refund) as patronage_refund, SUM(distribution) as distribution'))->first();
            $distribution = Distribution::where('confirmed', null)->first();
            // dd($pending);
            
            return view('users.admin.dashboard')->with('distribution', $distribution)->with('status', $status)->with('turn_over', $turn_over)->with('trans', $trans)->with('transactions', $transactions)->with('active', 'dashboard')->with('memReq', $memberRequests)->with('pending', $pending);
        } else if ( Auth::user()->user_type == 1 ) {
            $transactions = Transaction::join('users', 'users.id', '=', 'member_id' )->select('transactions.id', 'transactions.created_at', 'lname', 'fname', 'mname', 'trans_type', 'amount')->where([['collector_id', Auth::user()->id], ['confirmed', 1]])->orderBy('transactions.created_at', 'DESC')->paginate(10);
            return view('users.collector.dashboard')->with('transactions', $transactions)->with('active', 'dashboard');
        } else {
            $transactions = Transaction::join('users', 'users.id', '=', 'collector_id')->select('transactions.id', 'amount', 'lname', 'fname', 'mname', 'trans_type', 'transactions.created_at')->where('member_id', Auth::user()->id)->whereNotNull('confirmed')->orderBy('transactions.created_at')->paginate(5);
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
        $members = User::where([['user_type', '=', '0'], ['setup', 1]])->select('id','lname','fname','mname')->orderBy('id', 'desc')->get();
        
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Transaction::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================

        return view('users.collector.collect1')->with('active','collect')->with('members', $members)->with('token', $token)->with('found_id', null)->with('success', 'Member Found!');
    }

    // After the collector searched the member it shows collect2.blade.php
    public function partial_store(){
        
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Transaction::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================

        $memID = Input::get('memID');
        $request = new Request([
            'memID' => $memID,
        ]);
        
        $this->validate($request, [
            'memID' => 'required',
        ]);

        $member = User::where([['id', $memID], ['user_type',0], ['setup', 1]])->first();
        $loan_request = Loan_Request::where([['user_id', $member->id], ['confirmed', 1], ['paid', null], ['paid_using_savings', null], ['received', 1]])->first();
        $check_for_pending = Transaction::where([['member_id', $member->id], ['collector_id', Auth::user()->id], ['confirmed', null]])->get();
        
        // testing 
        //  1546333748 january
        // 1571418025 october
        //Check if per_month_date is beyond 1 month
        // dd($date = strtotime("+".$loan_request->days_payable." months", $loan_request->date_approved), $loan_request->date_approved, date("F d, Y", $date));
        if($loan_request ? !$loan_request->paid ? !$loan_request->paid_using_savings : '' : ''){
            if( strtotime(NOW()) > strtotime("+".$loan_request->days_payable." months", $loan_request->date_approved)){
                $status = Status::where('user_id', $memID)->first();
                $status->savings = $status->savings - $loan_request->balance; 
                $status->save();

                $loan_request->paid = 1;
                $loan_request->balance = 0;
                $loan_request->per_month_amount = 0;
                $loan_request->save();

            }else if($loan_request ? !$loan_request->paid ? !$loan_request->paid_using_savings ? strtotime(NOW()) > $loan_request->per_month_to : '' : '' : ''){

                // compute the monthly loan
                if($loan_request->per_month_amount <= 0){
                    $temp = ($loan_request->loan_amount * 0.06 * $loan_request->days_payable + $loan_request->loan_amount) / $loan_request->days_payable;
                    // subtract the negative value 
                    $loan_request->per_month_amount = $loan_request->per_month_amount + $temp;
                }else{
                    $temp = ($loan_request->loan_amount * 0.06 * $loan_request->days_payable + $loan_request->loan_amount) / $loan_request->days_payable;
                    $loan_request->per_month_amount = $temp + $loan_request->per_month_amount;
                }

                $loan_request->per_month_from = $loan_request->per_month_to;
                $loan_request->per_month_to = strtotime("+1 months", $loan_request->per_month_to);
                $loan_request->save();
                // dd($loan_request->per_month_amount, $loan_request->balance);
                // dd(strtotime(NOW()) > $loan_request->per_month_to ? $loan_request->per_month_amount == $loan_request->balance : 'hello');
                // dd(strtotime(NOW()) , $loan_request->per_month_to, strtotime(NOW()) > $loan_request->per_month_to, date('F d, Y', strtotime(NOW())),  date('F d, Y', $loan_request->per_month_to));
            }
        }

        return view('users.collector.collect2')->with('active', 'collect')->with('check_for_pending', $check_for_pending)->with('token', $token)->with('member', $member)->with('loan_request', $loan_request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // This check if the token is duplicate or not after the form is saved , This trick the collector think he submitted one form
        if(Transaction::where('token', $request->token)->first()){
            return redirect()->back()->with('success', 'Waiting to confirm from member');
        }
        
        $messages = [
            'required' => 'This field is required',
            'numeric' => 'This field is Numbers',
        ];
        // dd ($request->memID);
        $this->validate($request, [
            'amount' => ['required', 'numeric'],
            'type' => ['required','numeric'],
            'memID' => ['required', 'numeric'],
        ], $messages);
        
        $user = User::where([['id', $request->memID], ['user_type', 0]])->first();
        // This check if the requested id is a member or not 
        if(!$user){
            return redirect()->back()->withInput()->with('error', 'Member Not Found');
        }

    // ***************************      Start           ***************************
         if ($request->type == 1) { 
            // If the transaction is a DEPOSIT

            // This check for pending confirmation from the member: use for collector->collection
            $check_for_pending = Transaction::where([['member_id', $request->memID], ['collector_id', Auth::user()->id], ['confirmed', null]])->first();
            if($check_for_pending){
                return redirect()->back()->withInput()->with('check_for_pending', $check_for_pending)->with('error', 'Pending confirmation from the member');
            }

            $transact = New Transaction;
            $transact->member_id = $request->memID;
            $transact->collector_id = Auth::user()->id;
            $transact->trans_type = $request->type;
            $transact->amount = $request->amount;
            $transact->get = 0;
            $transact->token = $request->token;

            // Create a paid date
            $sched = new Schedule;
            $sched->user_id = $transact->member_id;
            $sched->sched_type = 1;     // [1] Deposit Schedule (See SchedulesController)
            $sched->start_date = Carbon::now()->format('Y-m-d');
            $sched->end_date = Carbon::now()->format('Y-m-d');
            $sched->save();
            
            // Get the sched_id
            $transact->sched_id = $sched->id;
            $transact->save();
            // return redirect()->back('transaction-c')->with('success', 'watint');
            return redirect()->back()->with('success', 'Waiting to confirm from the Member');
            
        } else if ($request->type == 3) {
            // If the transaction is a Loan Payment [ $trans_type = 3 ]

            // check if member has current loan, loan is confirmed by the admin and money received by the member
            if(Loan_Request::where([['user_id', $request->memID], ['confirmed', null], ['received', null]])->first()){
                return redirect()->back()->with('error', 'Loan Payment Not Available');
            }



            $transact = New Transaction;    
            $transact->member_id = $request->memID;
            $transact->trans_type = $request->type;
            $transact->token = $request->token;

            // Add new condition where transaction: member id is null or no existing loan payment transaction execute this code 
            if (Transaction::where('member_id','=', $request->memID)->first() == NULL) {

                // If this is the first transaction made by the member
                $loan_request = Loan_Request::where([
                    ['user_id', '=', $request->memID],
                    ['confirmed', '=', 1],
                    ['received', '=', 1],
                    ['get', '=', 0]
                ])->first();        // Get the loan_request

                // If $loan_request is NULL redirect and return error message
                if(!$loan_request){
                    return redirect()->back()->withInput()->with('error', 'Not Found');
                }else if(ceil($loan_request->balance) < $request->amount){
                    return redirect()->back()->withInput()->with('error', 'Loan Payment is beyond the Loan Balance');
                }

                // Need to confirm from the member to deduct the balance 
                // $loan_request->balance = $loan_request->balance - $transact->amount; Instead this moved from the accept()
                $transact->request_id = $loan_request->id;
                $transact->amount = $request->amount;
                $loan_request->get = 1;
                $loan_request->save();
            } else {
                // Member is continuing to pay his/her loan               

                // This check for pending confirmation from the member: use for collector->collection
                $check_for_pending = Transaction::where([['member_id', $request->memID], ['collector_id', Auth::user()->id], ['confirmed', null]])->first();
                if($check_for_pending){
                    return redirect()->back()->withInput()->with('check_for_pending', $check_for_pending)->with('error', 'Pending confirmation from the member');
                }

                $loan_request = Loan_Request::where([
                    ['user_id', '=', $request->memID],
                    ['confirmed', '=', 1],
                    ['paid', '=', null]
                ])->first();        // Get the latest update of the loan_request

                // This block the incoming transaction of loan payment if member not receive the loan money
                // if(!$loan_request->received){
                //     return redirect()->back()->with('error', "User doesn't have any payments");
                // }

                if (is_null($loan_request)) {
                    // There are no more loan payments by the user
                    return redirect()->back()->with('error', "User doesn't have any payments");
                } else if (ceil($loan_request->balance - $request->amount) < 0) {
                    // Prevents the collector to input the amount that is larger than the balance
                    return redirect()->back()->with('error', 'Amount entered is beyond the balance')->withInput();
                } else if (($loan_request->balance - $request->amount) == 0) {
                    // $loan_request->balance is moved in accept ()
                    // $loan_request->balance = $loan_request->balance - $request->amount;
                    // $loan_request->paid = 1;    // Payment is closed/done

                    $transact->amount = $request->amount;
                    $transact->request_id = $loan_request->id;
                } else {
                    // Payment continues
                    // $loan_request->balance = $loan_request->balance - $transact->amount;
                    // Instead to deduct the balance here it need to confirm from the member via accept()

                    $transact->amount = $request->amount;
                    $transact->request_id = $loan_request->id;
                }
                $loan_request->save();
            }
            
            $transact->get = 1;     // Serves as the basis for the next transaction? : get is not use anymore since the balance is being moved to loan_request table
            $transact->collector_id = Auth::user()->id;

            // Create a paid date
            $sched = new Schedule;
            $sched->user_id = $transact->member_id;
            $sched->sched_type = 3;     // [3] Paid Loan Schedule (See SchedulesController)
            $sched->start_date = Carbon::now()->format('Y-m-d');
            $sched->end_date = Carbon::now()->format('Y-m-d');
            $sched->save();

            // Get the sched_id
            $transact->sched_id = $sched->id;
            $transact->save();

            return redirect()->back()->with('success', 'Waiting to confirm from the member');
            
        } else {
            return redirect()->back()->with('error', 'Pick a valid Transaction Type');
        }
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

    // This function use to update confirmed column in transactions table 
    public function accept ($id, $token){
        if (!$transact = Transaction::where([['id','=', $id], ['confirmed','=', NULL]])->first()){
            return redirect()->back()->with('success', 'Confirmed Successfully');
        }
        $loan_request = Loan_Request::where('id', $transact->request_id)->first();
        $status = Status::where('user_id', Auth::user()->id)->first();
        $transact->confirmed = 1;
        $loan_request->token = $token;
        $loan_request->balance = $loan_request->balance - $transact->amount;
        $loan_request->per_month_amount = $loan_request->per_month_amount - $transact->amount;
        
        // if $loan_request->balance has no value change paid status to 1
        if($loan_request->balance <= 0){
            $loan_request->paid = 1;
            $loan_request->paid_using_savings = 1;
            
            // this code update the distribution amount in id 1, the constant/default row 
            $update_dis = Status::where('user_id', 1)->first();
            $update_dis->distribution = $update_dis->distribution + $loan_request->loan_amount * 0.06 * $loan_request->days_payable * 0.6;
            $update_dis->save();

            // this check if member status is already in DB
            if($status){
                // this code update the patronage amounr of the users
                $update_pat = Status::where('user_id', Auth::user()->id)->first();
                $update_pat->patronage_refund = $update_pat->patronage_refund + $loan_request->loan_amount * 0.06 * $loan_request->days_payable * 0.4;
                $update_pat->save();
            }else{
                // else new status row
                $update_pat = new Status;
                $update_pat->user_id = Auth::user()->id;
                $update_pat->patronage_refund = $update_pat->patronage_refund + $loan_request->loan_amount * 0.06 * $loan_request->days_payable * 0.4;
                $update_pat->save();
            }
        }

        $loan_request->save();
        $transact->save();
        return redirect()->back()->with('success', 'Confirmed Successfully');
    }

    // For deposit confirmation
    public function deposit_accept($id){
        $transact = Transaction::where([['id', $id], ['confirmed', NULL]])->first();
        if(!$transact){
            return redirect()->back()->with('success', 'Confirmed Successsfully');
        }

        $transact->confirmed = 1;

        // If member/user_id exist execute this code and update the status
        $status = Status::where('user_id', $transact->member_id)->first();
        if($status){
            $status->savings = $status->savings + $transact->amount;
        }else{
            // Create new status data
            $status = New Status;
            $status->user_id = Auth::user()->id;
            $status->savings = $status->savings + $transact->amount;
        }
        
        $status->save();
        $transact->save();
        
        return redirect()->back()->with('success', 'Confirmed Successsfully');
    }

    public function generatepdf($id){
        // decrypt the encrtypted id
        $decrypt = Crypt::decrypt($id);

        $trans = Transaction::where('id', $decrypt)->first();
        $mem = User::where('id', $trans->member_id)->first();
        $col = User::where('id', $trans->collector_id)->first();

        $pdf = new FPDF('L','mm',array(100,150));		// can set the layout of the PDF 
		$pdf->AddPage();			//can add another page
		$pdf->SetFont('Arial','',12); //set font of your PDF
		$margin = 10;
		$x = 150;

		//header
		$text = 'Sinking Fund';
		$pdf->Text($x*0.45, $margin, $text);

		$text = 'Poblacion Compostela Cebu';
		$pdf->Text($x*0.35, 20, $text);

		//Partial Details
		$text = 'Member ID:';
        $pdf->Text($margin, 35, $text);
        $text = $trans->member_id;
		$pdf->Text($margin + 40, 35, $text);

		$text = 'Date';
        $pdf->Text($x*0.65, 35, $text);
        $text = date('F d, Y', strtotime($trans->created_at));
        $pdf->Text($x*0.75, 35, $text);

        $text = 'Time: ';
        $pdf->Text($x*0.65, 40, $text);
        $text = date('h:i A', strtotime($trans->created_at));
		$pdf->Text($x*0.75, 40, $text);

		//Full Details
		$text = 'Complete Name:';
        $pdf->Text($margin, 50, $text);
        $text = $mem->lname. ', '. $mem->fname.' '.$mem->mname;
		$pdf->Text($margin + 40, 50, $text);

		$text = 'Type:';
        $pdf->Text($margin, 60, $text);
        if ($trans->trans_type == 3){
            $text = 'Loan Payment';
        }else if($trans->trans_type == 1){
            $text = 'Deposit';
        }
        $pdf->Text($margin + 40, 60, $text);

		$text = 'Amount:';
        $pdf->Text($margin, 70, $text);
        $text = 'Php '.$trans->amount;
        $pdf->Text($margin + 40, 70, $text);

        $text = 'Receive By: ';
        $pdf->Text($margin, 80, $text);
        $text = $col->id.' '.$col->lname.', '.$col->fname.' '.$col->mname;
        $pdf->Text($margin + 40, 80, $text);
        
        $filename = $mem->lname."'s Receipt.pdf";
        $pdf->Output('D', $filename, True);		//to close document
                
    }
}
