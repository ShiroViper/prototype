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
            'email' => ['required', 'string', 'unique:users', 'unique:member__requests,email', 'email'],
            'cell_num' => ['required', 'string', 'numeric', 'unique:member__requests,contact', 'unique:users', 'digits:11'],
            'email' => ['required', 'string', 'unique:member__requests,email', 'unique:users', 'email'],
            'street_number' => ['required', 'string'],
            'barangay' => ['required', 'string'],
            'city_town' => ['required', 'string'],
            'province' => ['required', 'string'],
            'face_photo' => 'image|required|max:1999',
            'front_id_photo' => 'image|required|max:1999',
            'back_id_photo' => 'image|required|max:1999',
            'id_type' => ['required', 'string'],
            'referral_email' => ['nullable', 'string', 'email'],
            'referral_num' => ['nullable', 'string', 'numeric', 'digits:11'],
            'password'=>['required', 'string', 'min:4'],
        ], $messages);

        // dd($request->id_type);

        if($request->hasFile('face_photo')){
            // Get filename with the extension
            // $filenameWithExt = $request->input('email');
            $filenameWithExt =  'face_'.$request->input('email');
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('face_photo')->getClientOriginalExtension();
            //Filename to store
            $face_photo = $filename.'.'.$extension;
            //Upload Image
            $path = $request->file('face_photo')->storeAs('public\face_images',$face_photo);            
        } 

        if($request->hasFile('front_id_photo')){
            // Get filename with the extension
            // $filenameWithExt = $request->input('email');
            $filenameWithExt2 = 'front_'.$request->input('email');
            //Get just filename
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            // Get just ext
            $extension2 = $request->file('front_id_photo')->getClientOriginalExtension();
            //Filename to store
            $front_id_photo = $filename2.'.'.$extension2;
            //Upload Image
            $path2 = $request->file('front_id_photo')->storeAs('public\id_images',$front_id_photo);            
        } 

        if($request->hasFile('back_id_photo')){
            // Get filename with the extension
            $filenameWithExt3 = 'back_'.$request->input('email');
            //Get just filename
            $filename3 = pathinfo($filenameWithExt3, PATHINFO_FILENAME);
            // Get just ext
            $extension3 = $request->file('back_id_photo')->getClientOriginalExtension();
            //Filename to store
            $back_id_photo = $filename3.'.'.$extension3;
            //Upload Image
            $path3 = $request->file('back_id_photo')->storeAs('public\id_images',$back_id_photo);            
        } 

        $mem_req = new Member_Request;
        $mem_req->lname = $request->input('lname');
        $mem_req->fname = $request->input('fname');
        $mem_req->mname = $request->input('mname');
        $mem_req->referral_email = $request->input('referral_email');
        $mem_req->referral_num = $request->input('referral_num');

        $mem_req->email = $request->input('email');
        $mem_req->password = Hash::make($request->password);
        
        $mem_req->contact = $request->input('cell_num');
        $mem_req->street_number = $request->input('street_number');
        $mem_req->barangay = $request->input('barangay');
        $mem_req->city_town = $request->input('city_town');
        $mem_req->province = $request->input('province');
        $mem_req->face_photo = $face_photo;
        $mem_req->front_id_photo = $front_id_photo;
        $mem_req->back_id_photo = $back_id_photo;
        $mem_req->id_type = $request->input('id_type');
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
        $new->password = $req->password;
        $new->user_type = 0;
        $new->lname = $req->lname;
        $new->fname = $req->fname;
        $new->mname = $req->mname;
        
        $new->cell_num = $req->contact;
        $new->email = $req->email;
        $new->street_number = $req->street_number;
        $new->barangay = $req->barangay;
        $new->city_town = $req->city_town;
        $new->province = $req->province;
        $new->referral_num = $req->referral_num;
        $new->referral_email = $req->referral_email;
        $new->setup = 1;

        
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
