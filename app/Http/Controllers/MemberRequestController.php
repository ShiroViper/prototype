<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member_Request;
use App\User;
use Hash;

class MemberRequestController extends Controller
{
    public function memberRequest(Request $request)
    {
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters',
            'regex' => 'Numeric characters and symbols are not allowed',
            'cell_num.unique' => 'That contact number was already registered',
            'email.unique' => 'That email was already registered',
            'numeric' => 'Please input a valid contact number'
        ];
        
        $this->validate($request, [
            'lname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'fname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'mname' => ['nullable', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'string', 'unique:users', 'email'],
            'cell_num' => ['required', 'string', 'numeric', 'unique:member__requests,contact', 'unique:users', 'digits:11'],
            'address' => ['required', 'string']
        ], $messages);

       

        $this->validate($request,[
            'face_photo' => 'image|nullable|max:1999' 
        ]);
        if($request->hasFile('face_photo')){
            // Get filename with the extension
            $filenameWithExt = $request->file('face_photo')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('face_photo')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //Upload Image
            $path = $request->file('face_photo')->storeAs('public/cover_images',$fileNameToStore);            
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

    

        $mem_req = new Member_Request;
        $mem_req->lname = $request->input('lname');
        $mem_req->fname = $request->input('fname');
        $mem_req->mname = $request->input('mname');
        $mem_req->email = $request->input('email');
        $mem_req->contact = $request->input('cell_num');
        $mem_req->address = $request->input('address');
        $mem_req->face_id = $fileNameToStore;
        $mem_req->save();

        return redirect('/')->with('success', 'Request submitted successfully');
    }

    public function accept($id)
    {
        $users = User::orderBy('id', 'desc')->take(1)->get();

        /**
         * Get the latest ID number, increment and put it 
         * in the view page
         */
        foreach($users as $user) {
            $last_id = $user->id;
            $get_year = now()->year - 2000;
            $result = floor($last_id/10000);
            // result in 2 digit number

            // echo $result."<br>"; 
            
            if ( $result == $get_year ) {
                // echo "true";
                $ctr = $result * 10000;
                $result = ($get_year*10000)+(($last_id - $ctr)+1);

            } else {
                // echo "false";
                $result = $get_year*10000;
                $result++;
            }
        }

        $req = Member_Request::find($id);

        $new = new User;
        $new->id = intval($result);
        $new->password = Hash::make($req->email);
        $new->user_type = 0;
        $new->lname = $req->lname;
        $new->fname = $req->fname;
        $new->mname = $req->mname;
        $new->cell_num = $req->contact;
        $new->email = $req->email;
        $new->address = $req->address;
        $new->save();

        $req->approved = true;
        $req->save();

        return redirect()->action('UsersController@index')->with('success', 'User added successfully');
    }

    public function decline($id)
    {
        $member_request = Member_Request::where('id', $id)->first();
        $member_request->delete();

        return redirect()->back()->with('success', 'Member Decline Successfully');
    }
}
