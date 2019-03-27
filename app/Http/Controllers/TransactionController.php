<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Str;
use App\Status;
use App\Loan_Request;
use App\User;
use App\Schedule;

class TransactionController extends Controller
{
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
            ->where(DB::raw('ADDDATE(loan_request.created_at, days_payable)'), '<', NOW())
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);
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
            ->where(DB::raw('ADDDATE(loan_request.created_at, 30)'), '<', NOW())
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);
            // No Values if transactions table empty

        return view('users.collector.deliquent')->with('deliquents', $deliquents)->with('active', 'deliquent');
    }
    public function index()
    {
        if ( Auth::user()->user_type == 2 ) {
            $transactions = Transaction::where('confirmed',1)->orderBy('id', 'desc')->paginate(10);
            return view('users.admin.dashboard')->with('transactions', $transactions)->with('active', 'dashboard');
        } else if ( Auth::user()->user_type == 1 ) {
            // return dd($transactions);
            $transactions = Transaction::where([['collector_id', Auth::user()->id], ['confirmed', 1]])->orderBy('created_at', 'DESC')->paginate(10);
            return view('users.collector.dashboard')->with('transactions', $transactions)->with('active', 'dashboard');
        } else {
            // $transactions = Transaction::where([['member_id', '=', Auth::user()->id], ['confirmed',1]])->paginate(10);
            $transactions = DB::table('transactions')->join('users', 'users.id', '=', 'collector_id')
                ->select('amount', 'lname', 'fname', 'mname', 'trans_type', 'transactions.created_at')
                ->where('member_id', Auth::user()->id)->whereNotNull('confirmed')
                ->paginate(5);
            // dd($transactions);
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
        $members = User::where('user_type', '=', '0')->select('id','lname','fname','mname')->orderBy('id', 'desc')->get();
        
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Transaction::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================


        // return dd($members);
        return view('users.collector.collect')->with('active','collect')->with('members', $members)->with('token', $token);
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

        // // This check if the requested id is a member or not 
        // if(!User::where([['id', $request->id], ['user_type', 0]])->first()){
        //     return redirect()->back()->withInput()->with('error', 'Member Not Found');
        // }

        $messages = [
            'required' => 'This field is required',
            'numeric' => 'This field is Numbers',
        ];
        $this->validate($request, [
            'amount' => ['required', 'numeric'],
            'type' => ['required','numeric'],
            'id' => ['required', 'numeric'],
        ], $messages);

    // ***************************      Start           ***************************
         if ($request->type == 1) { 
            // If the transaction is a DEPOSIT

            $transact = New Transaction;
            $transact->member_id = $request->id;
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
            
            return redirect()->back()->with('success', 'Waiting to confirm from the Member');
            
        } else if ($request->type == 3) {

            // 

            // If the transaction is a Loan Payment [ $trans_type = 3 ]
            $transact = New Transaction;    
            $transact->member_id = $request->id;
            $transact->trans_type = $request->type;
            $transact->token = $request->token;

            // Add new condition where transaction: member id is null or no existing loan payment transaction execute this code 
            if (Transaction::where('member_id','=', $request->id)->first() == NULL) {

                // If this is the first transaction made by the member
                $loan_request = Loan_Request::where([
                    ['user_id', '=', $request->id],
                    ['confirmed', '=', 1],
                    ['received', '=', 1],
                    ['get', '=', 0]
                ])->first();        // Get the loan_request

                // If $loan_request is NULL redirect and return error message
                if(!$loan_request){
                    return redirect()->back()->withInput()->with('error', 'Not Found');
                }

                // Need to confirm from the member to deduct the balance 
                // $loan_request->balance = $loan_request->balance - $transact->amount; Instead this moved from the accept()
                $transact->request_id = $loan_request->id;
                $transact->amount = $request->amount;
                $loan_request->get = 1;
                $loan_request->save();
            } else {
                // return $transact->member_id;
                // Member is continuing to pay his/her loan
                $loan_request = Loan_Request::where([
                    ['user_id', '=', $request->id],
                    ['confirmed', '=', 1],
                    ['paid', '=', null]
                ])->first();        // Get the latest update of the loan_request
                if (is_null($loan_request)) {
                    // There are no more loan payments by the user
                    return redirect()->route('transaction-collect')->with('error', "User doesn't have any payments");
                } else if (($loan_request->balance - $request->amount) < 0) {
                    // Prevents the collector to input the amount that is larger than the balance
                    return redirect()->route('transaction-collect')->with('error', 'Amount entered is beyond the balance')->withInput();
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

            return redirect()->route('transaction-collect')->with('success', 'Waiting to confirm from the member');
            
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
    public function accept ($id){
        $transact = Transaction::where([['id','=', $id], ['confirmed','=', NULL]])->first();
        $loan_request = Loan_Request::where('id', $transact->request_id)->first();
        $transact->confirmed = 1;
        $loan_request->balance = $loan_request->balance - $transact->amount;
        // return 'hi';

        // if $loan_request->balance has no value change paid status to 1
        if(!$loan_request->balance){
            $loan_request->paid = 1;
            
            // this code update the distribution amount in id 1, the constant/default row 
            $update_dis = Status::where('user_id', 1)->first();
            $update_dis->distribution = $update_dis->distribution + $loan_request->loan_amount * 0.06 * 0.6;
            $update_dis->save();

            // this code update the patronage amounr of the users
            $update_pat = Status::where('user_id', Auth::user()->id)->first();
            $update_pat->patronage_refund = $update_pat->patronage_refund + $loan_request->loan_amount * 0.06 * 0.4;
            $update_pat->save();
        }

        $loan_request->save();
        $transact->save();
        return redirect()->back()->with('success', 'Confirmed Successfully');
    }

    // For deposit confirmation
    public function deposit_accept($id){
        $transact = Transaction::where([['id', $id], ['confirmed', NULL]])->first();
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

    public function generatepdf($m, $m_name, $c_name){

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
        $text = $m->member_id;
		$pdf->Text($margin + 40, 35, $text);

		$text = 'Date: ';
        $pdf->Text($x*0.65, 35, $text);
        $text = date('M d, Y', strtotime($m->created_at));
        $pdf->Text($x*0.75, 35, $text);

        $text = 'Time: ';
        $pdf->Text($x*0.65, 40, $text);
        $text = date('h:i:s A', strtotime($m->created_at));
		$pdf->Text($x*0.75, 40, $text);

		//Full Details
		$text = 'Complete Name:';
        $pdf->Text($margin, 50, $text);
        $text = $m_name->lname. ', '. $m_name->fname;
		$pdf->Text($margin + 40, 50, $text);

		$text = 'Type:';
        $pdf->Text($margin, 60, $text);
        if($m->trans_type == 0){
            $text = 'Loan Widthdraw';
        }else if ($m->trans_type == 1){
            $text = 'Loan Payment';
        }else{
            $text = 'Deposit';
        }
		$pdf->Text($margin + 40, 60, $text);

		$text = 'Amount:';
        $pdf->Text($margin, 70, $text);
        $text = 'Php '.$m->amount;
        $pdf->Text($margin + 40, 70, $text);

        $text = 'Receive By: ';
        $pdf->Text($margin, 80, $text);
        $text = $c_name->lname.', '.$c_name->fname;
        $pdf->Text($margin + 40, 80, $text);
        
        $filename = "receipt/Receipt.pdf";
        $pdf->Output('F', $filename, True);		//to close document
                
    }
}
