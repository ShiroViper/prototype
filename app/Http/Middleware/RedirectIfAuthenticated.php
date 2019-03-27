<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->user_type == 2) {
                return redirect()->route('admin-dashboard');
            }
            else if (Auth::user()->user_type == 1) {
                return redirect()->route('collector-dashboard');
            }
            else {
                // check if inactive is true or false
                if (!Auth::user()->inactive == 1){
                    return redirect()->route('member-dashboard');
                }else{
                    return redirect()->route('member-cancel');
                }
            }
        }

        return $next($request);
    }   
}
