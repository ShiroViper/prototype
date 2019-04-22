<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Hash;
use App\User;
use App\Status;
use App\Comment;


class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = DB::table('transactions')
            ->select('trans_type', 'transactions.created_at', 'lname', 'fname', 'amount', 'balance')
            ->join('loan_request','user_id', '=', 'member_id')
            ->join('users as collector', 'collector.id', '=', 'collector_id')
            ->where('member_id', Auth::user()->id)
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(10);
            // No Values if transactions table empty

        return view('users.member.transaction')->with('transactions', $transactions)->with('active', 'transactions');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $messages = [
            'required' => 'This field is required',
        ];
        $this->validate($request, [
            'reason' => ['required'],
            'pass' => ['required'],
            'token' => ['required'],
        ], $messages);
        
        // This check if the token is duplicate or not after the form is saved , This trick the collector think he submitted one form
        if(Comment::where('token', $request->token)->first()){
            return redirect()->back()->with('success', 'Goodbye Fellow Member');
        }

        // This check if the requested password is match from the auth password
        if(!hash::check($request->pass, Auth::user()->password)){
            return redirect()->back()->with('error', 'Wrong Password')->withInput(Input::except('pass'));
        }

        //  Create new archive comment
        $archive = New Comment;
        $archive->user_id = Auth::user()->id;
        $archive->comments = $request->reason;
        $archive->token = $request->token;
        $archive->save();

        // This update the inactive status to true/1
        // $update_archive = User::where('id', Auth::user()->id)->first();
        // $update_archive->inactive = 1;
        // $update_archive->save();

        return redirect()->back()->with('success', 'Requires confirmation from the administration');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {        
        //  Destroy requested cancellation of account
        $destroy = Comment::where('user_id', Auth::user()->id)->first();
        $user = User::where('id', Auth::user()->id)->first();
        if($user->inactive || !$destroy){
            return redirect()->back()->with('error', 'Revoke Cancellation of account Not available');
        }

        $destroy->delete();

        return redirect()->back()->with('success', 'Successfully Revoked cancellation of account requests');
    }

    public function cancel(){
        
        /* This for finding duplicate token in table */
        $token = Str::random(10);
        $check_token = Comment::select('token')->get();
        for ($x = 0; $x < count($check_token); $x++){
            if($check_token[$x]->token == $token){
                $token = Str::random(10);
            }
        }
        // ================================================

        // 
        $account_status = Comment::where('user_id', Auth::user()->id)->first();
        // dd($account_status);
        
        if (Auth::user()->user_type == 1) {
            return view('users.collector.cancel')->with('account_status', $account_status)->with('token',$token)->with('active', 'cancel');    
        } else {
            return view('users.member.cancel')->with('account_status', $account_status)->with('token',$token)->with('active', 'cancel');
        }
    }

    public function accept($id){
        $confirm = Comment::where('id', $id)->first();
        $user = User::where('id', $confirm->user_id)->first();
        $status = Status::where('user_id', $confirm->user_id)->first();

        $confirm->confirmed = 1;
        $user->inactive = 1;
        
        if($status->savings >= 1825){
            $status->savings = 0;
            $status->patronage_refund = 0;
        }else{
            $status->savings = 0;
        }

        $status->save();
        $user->save();
        $confirm->save();
        
        return redirect()->back()->with('success', 'Cancellation of account confirmed');        
    }

    public function reject($id){
        $reject = Comment::where('id', $id)->first();
        // dd($reject);
        $reject->delete();

        return redirect()->back()->with('success', 'Cancellation of account declined');
    }

    public function changePassword()
    {
        $user = User::find(Auth::user()->id)->first();
        return view('change-password')->with('user', $user)->with('active', null);
    }

    public function change(Request $request)
    {
        $messages = [
            'required' => 'This field is required',
        ];
        $this->validate($request, [
            'old_password'=>['required', 'string'],
            'password'=>['required', 'string', 'min:4'],
            'password_confirmation'=>['required', 'string'],
        ], $messages);

        if(!Hash::check($request->old_password, Auth::user()->password)){
            return redirect()->back()->with('error', 'Old password not matched');
        }else if($request->password != $request->password_confirmation){
            return redirect()->back()->with('error', 'New password not matched');
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->password = bcrypt($request->password_confirmation);
        $user->save();
        
        return redirect()->route('profile-index')->with('success', 'Password changed Successfully');
        
    }
}
