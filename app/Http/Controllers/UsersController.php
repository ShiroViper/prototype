<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use DB;
use Illuminate\Validation\Rule;
use App\User;
use App\Status;
use Hash;
use Validator;
use Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Other methods
         * $users = User::orderBy('id', 'desc')->get();
         * $users = User::where('id', 190001)->get();
         * 
         * Requires DB
         * $users = DB::select('SELECT * FROM users');
         */

        // $users = User::orderBy('id', 'desc')->take(1)->get();

        $users = User::orderBy('id', 'desc')->whereIn('user_type', [1,0])->paginate(10);
        return view('users.admin.manage')->with('users', $users)->with('active', 'manage');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        return view('users.admin.create')->with('users', $users)->with('active', 'create')->with('result', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters',
            'regex' => 'Numeric characters and symbols are not allowed'
        ];

        $this->validate($request, [
            'lname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'fname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'mname' => ['nullable', 'string'],
            'cell_num' => ['required', 'string', 'numeric', 'digits:11'],
            'email' => ['required', 'string', 'unique:users', 'email'],
            'address' => ['required', 'string'],
            'password'=>['required', 'string', 'min:4'],
        ], $messages);

        $user = new User;
        $user->id = $request->input('id');
        $user->user_type = $request->input('type');
        $user->lname = $request->input('lname');
        $user->fname = $request->input('fname');
        $user->mname = $request->input('mname');
        $user->password = Hash::make($request->input('email'));
        $user->email = $request->input('email');
        $user->cell_num = $request->input('cell_num');
        $new->street_number = $request->input('street_number');
        $new->barangay = $req->input('barangay');
        $new->city_town = $req->input('city_town');
        $new->province = $req->input('province');
        $new->referral_num = $req->input('referral_num');
        $new->referral_email = $req->input('referral_email');
        $user->save();

        // this will create a new status row in status column for his/her savings and patronage refund
        $new_status = New Status;
        $new_status->user_id = $request->input('id');
        $new_status->save();

        return redirect()->route('users-index')->with('success', 'User added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        // return dd($user);
        return view('users.admin.view')->with('user', $user)->with('active', 'manage');
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
        return view('users.admin.edit')->with('user', $user)->with('active', 'manage');
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
            'alpha' => 'Please use only alphabetic characters',
            'regex' => 'Numeric characters and symbols are not allowed'
        ];

        // $this->validate($request, [
        //     'lname' => ['required', 'string', 'alpha'],
        //     'fname' => ['required', 'string', 'alpha'],
        //     'mname' => ['nullable', 'string', 'alpha'],
        //     'cell_num' => ['required', 'string', 'numeric', 'digits:11'],
        //     'email' => ['required', 'string', 'exists:users', 'email'],
        //     'address' => ['required', 'string'],
        // ], $messages);

        $validator = Validator::make($request->all(), [
            'lname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'fname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'mname' => ['nullable', 'string'],
            'inactive' => ['required'],
            'cell_num' => ['required', 'string', 'numeric', 'digits:11'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'address' => ['required', 'string'],
        ], $messages);

        if ($validator->fails()) {
            // return redirect('admin/users/'.$id.'/edit')
            //             ->withErrors($validator)
            //             ->withInput();
            // return dd('failed');
            return back()->withInput()->withErrors($validator->errors());
        }

        $user = User::find($id);
        // $user->user_type = $request->input('user_type');
        $user->lname = $request->input('lname');
        $user->fname = $request->input('fname');
        $user->mname = $request->input('mname');
        $user->email = $request->input('email');
        $user->cell_num = $request->input('cell_num');
        $user->address = $request->input('address');
        $user->inactive = $request->input('inactive');
        $user->save();
        
        // return redirect()->route('users-index')->with('success', 'User updated successfully');
        return redirect()->action('UsersController@show', ['id' => $id])->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users-index')->with('success', 'User removed successfully');
    }

    /**
     * Setup user Account
     */
    public function setup(Request $request)
    {
        // return dd($request);

        $messages = [
            'required' => 'This field is required',
            'alpha' => 'Please use only alphabetic characters',
            'regex' => 'Numeric characters and symbols are not allowed'
        ];

        $this->validate($request, [
            'lname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'fname' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'mname' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'cell_num' => ['required', 'string', 'numeric', 'digits:11'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::user()->id)],
            'address' => ['required', 'string'],
        ], $messages);

        $user = User::find(Auth::user()->id);
        $user->lname = $request->input('lname');
        $user->fname = $request->input('fname');
        $user->mname = $request->input('mname');
        $user->password = Hash::make($request->input('password'));
        $user->email = $request->input('email');
        $user->cell_num = $request->input('cell_num');
        $user->address = $request->input('address');
        
        // Create new status data 
        $status = new Status;
        $status->user_id = Auth::user()->id;
        $status->save();

        // Account already setup'ed
        $user->setup = true;
        $user->save();

        return redirect()->route('member-dashboard')->with('success', 'Account updated successfully');
    }

    /**
     * Set this user to inactive
     */
    public function inactive($id)
    {
        $user = User::find($id);
        $user->inactive = true;
        $user->save();
        return view('users.admin.view')->with('user', $user)->with('active', 'manage');
    }

    /**
     * Set this user to active
     */
    public function active($id)
    {
        $user = User::find($id);
        $user->inactive = false;
        $user->save();
        return view('users.admin.view')->with('user', $user)->with('active', 'manage');
    }
}
