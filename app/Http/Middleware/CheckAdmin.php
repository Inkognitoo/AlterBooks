<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAdmin
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
        if (!(bool)optional(Auth::user())->is_admin) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}
