<?php

namespace App\Http\Controllers;

use App\Status;
use App\Loan_Request;
use Auth;
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

                // dd($savings, $patronage, $loan);

                return view('users.member.status')->with('loan', $loan)->with('savings', $savings)->with('patronage', $patronage)->with('active', 'status');
            break;

            case 1:
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
}
