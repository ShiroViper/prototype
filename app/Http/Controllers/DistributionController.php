<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Distribution;
use App\Status;
use Auth;

class DistributionController extends Controller
{
    public function accept($id){
        $status = Status::where('user_id', 1)->first();
        $dis = Distribution::where([['user_id', Auth::user()->id], ['confirmed', null]])->first();
        $status_member = Status::where('user_id', Auth::user()->id)->first();
        
        if($dis){
            $dis->confirmed = 1;
            $status->distribution = $status->distribution - $dis->amount;
            $status_member->savings = 0;
            $status_member->patronage_refund = 0;

            $dis->save();
            $status->save();
            $status_member->save();

            return redirect()->back()->with('success', 'Confirmed Successfully');    
        }else{
            return redirect()->back()->with('success', 'Confirmed Successfully');    
        }
    }
}
