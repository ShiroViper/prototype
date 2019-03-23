<?php

namespace App\Http\Middleware;

use Closure;

class CollectorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Restricts Users from accessing URLs
         * and redirects them back if they are
         * not an a Collector
         */
        if ($request->user()->user_type != 1) {
            return redirect()->back()->with('error', 'Unauthorized Access!');
        }

        /**
         * If you wish to leave a note to the user
         * 
         * @return string
         * 
         * return redirect()->back()->with('error', 'Unauthorized Access!');
         */

        return $next($request);
    }
}
