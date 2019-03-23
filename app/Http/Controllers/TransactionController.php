<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Loan_Request;
use App\Schedule;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\User;

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
            $transactions = Transaction::where([['member_id', '=', Auth::user()->id], ['confirmed',1]])->paginate(10);
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
        // return dd($members);
        return view('users.collector.collect')->with('active','collect')->with('members', $members);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // return dd($request);
        $messages = [
            'required' => 'This field is required'
        ];
        $this->validate($request, [
            'amount' => ['required', 'numeric'],
            'id' => ['required', 'numeric'],
        ], $messages);
        
        // if($request->amount <= 49){
        //     return redirect()->route('transaction-collect')->with('active', 'collect')->with('error', 'Please pay above 50.00 Php')->withInput();
        // }

    // ***************************      Start           ***************************
         if ($request->type == 1) { 
            // If the transaction is a DEPOSIT
            $transact = New Transaction;    
            $transact->trans_type = $request->type;
            $transact->member_id = $request->id;
            $transact->amount = $request->amount;
        } else if ($request->type == 3) {
            // If the transaction is a Loan Payment [ $trans_type = 3 ]
            $transact = New Transaction;    
            $transact->member_id = $request->id;
            $transact->trans_type = $request->type;
            // Add new condition where 
            if (Transaction::where('member_id','=', $request->id)->first() == NULL) {
                // $temp = Transaction::where('member_id','=', $request->id)->first();
                // return dd($temp);
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
                // $loan_request->balance = $loan_request->balance - $transact->amount;
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
                    // return 'hi';
                    // $loan_request->balance = $loan_request->balance - $request->amount;
                    // $loan_request->paid = 1;    // Payment is closed/done
                    // Moved to accept()
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
        } else {
            return redirect()->back()->with('error', 'Pick a valid Transaction Type');
        }
        $transact->get = 1;     // Serves as the basis for the next transaction?
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

        // return dd($sched, $transact);

        return redirect()->route('transaction-collect')->with('success', 'Waiting to confirm from the member');
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

        // if $loan_request->balance has no value change paid status to 1
        if(!$loan_request->balance){
            $loan_request->paid = 1;
        }

        $loan_request->save();
        $transact->save();
        return redirect()->back()->with('success', 'Confirmed Successfully');
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
