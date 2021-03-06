<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;  
use Illuminate\Validation\Rule;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        return view('view-profile')->with('active','profile')->with('user', $user);
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
        $user = User::find($id);
        return view('edit-profile')->with('active','profile')->with('user', $user);
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
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters'
        ];

        $this->validate($request, [
            'lname' => ['required', 'string', 'alpha'],
            'fname' => ['required', 'string', 'alpha'],
            'cell_num' => ['required', 'string', 'numeric', 'digits:11'],
            'street_number' => ['required', 'string'],
            'barangay' => ['required', 'string'],
            'city_town' => ['required', 'string'],
            'province' => ['required', 'string'],
        ], $messages);
        
        $user = User::find($id);
        $user->lname = $request->lname;
        $user->fname = $request->fname;
        $user->mname = $request->mname;
        $user->user_type = Auth::user()->user_type;
        $user->street_number = $request->input('street_number');
        $user->barangay = $request->input('barangay');
        $user->city_town = $request->input('city_town');
        $user->province = $request->input('province');
        $user->cell_num = $request->cell_num;
        $request->email ? $user->email = $request->email : '';
        $user->save();

        return redirect()->route('profile-index')->with('active', 'profile')->with('success', 'Successfully updated your profile!');
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
