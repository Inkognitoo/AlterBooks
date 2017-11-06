<?php

namespace App\Http\Middleware;

use App\Book;
use Auth;
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
        $book = Book::findAny($request->id);

        if (blank($book)) {
            return response(view('errors.404'), 404);
        }

        if ($book->status == Book::CLOSE_STATUS && optional(Auth::user())->id != $book->author_id) {
            return response(view('errors.404'), 404);
        }

        return $next($request);
    }
}
