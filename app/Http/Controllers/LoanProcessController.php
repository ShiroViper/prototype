<?php

namespace App\Http\Controllers;

use App\Loan_Process;
use App\Loan_Request;
use Illuminate\Http\Request;
use Auth;

class LoanProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // return dd ($request->request);
        if($request->type == 0){

        }else{
            $process = New Loan_Process;
            $process->admin_id = Auth::user()->id;
            $process->collector_id = $request->c_id;
            $process->member_id = $request->m_id;
            $process->process_type = $request->type;
            $process->loan_amount = $request->amount;
            $process->save();

            $received = Loan_Request::where('user_id', '=', $request->m_id);
            $received->received = 1;
            $received->save();

            return redirect()->route('admin-create')->with('success', 'Successfully transfer');
        }
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
    public function edit(Loan_Process $loan_Process)
    {
        //
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
}
