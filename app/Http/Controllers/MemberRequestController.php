<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member_Request;

class MemberRequestController extends Controller
{
    public function memberRequest(Request $request)
    {
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters',
            'regex' => 'Numeric characters and symbols are not allowed',
            'unique' => 'That email is already taken'
        ];

        $this->validate($request, [
            'lname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'fname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'mname' => ['nullable', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'string', 'unique:users', 'email'],
        ], $messages);

        $mem_req = new Member_Request;
        $mem_req->lname = $request->input('lname');
        $mem_req->fname = $request->input('fname');
        $mem_req->mname = $request->input('mname');
        $mem_req->email = $request->input('email');
        $mem_req->save();
        // return dd($mem_req);
        return redirect('/')->with('success', 'Request submitted successfully');
    }
}
