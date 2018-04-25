<?php

namespace App\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;

class LastAction
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
        if (filled(Auth::user())) {
            Auth::user()->last_activity_at = Carbon::now();
            Auth::user()->save();
        }
        return $next($request);
    }
}
