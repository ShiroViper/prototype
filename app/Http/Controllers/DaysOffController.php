<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\DaysOff;

use Carbon\Carbon;
use DateInterval;

class DaysOffController extends Controller
{
    public function available(Request $request)
    {

        // $start = $request->av_start;
        // $end = $request->av_end;
        // $interval = new DateInterval('P1D');
        // $dayofweek = Carbon::parse($start)->dayOfWeek;

        return dd($request);
    }
}
