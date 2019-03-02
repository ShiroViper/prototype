<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function dashboard() {
        return view('users.member.dashboard')->with('active', 'dashboard');
    }

    public function transactions() {
        return view('users.member.transactions')->with('active', 'transactions');
    }

    public function loan() {
        return view('users.member.loan')->with('active', 'loan');
    }
}
