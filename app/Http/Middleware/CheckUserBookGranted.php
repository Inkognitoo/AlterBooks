<?php

namespace App\Http\Middleware;

use App\Book;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserBookGranted
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
        if (Auth::user()->id != Book::find($request->id)->author->id) {
            return response(view('errors.401'), 401);
        }

        return $next($request);
    }
}
