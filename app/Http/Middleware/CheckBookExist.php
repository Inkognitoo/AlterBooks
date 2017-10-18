<?php

namespace App\Http\Middleware;

use App\Book;
use Closure;

class CheckBookExist
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

        if (empty(Book::find($request->id))) {
            return response(view('errors.404'), 404);
        }

        return $next($request);
    }
}
