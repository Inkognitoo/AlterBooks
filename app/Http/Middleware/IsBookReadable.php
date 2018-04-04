<?php

namespace App\Http\Middleware;

use Closure;
use App\Book;

class IsBookReadable
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
        $book_id = $request->book_id ?? $request->id;
        $book = Book::findByIdOrSlug($book_id);

        if (!$book->isReadable()) {
            return response(view('errors.404'), 404);
        }

        return $next($request);
    }
}
