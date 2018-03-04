<?php

namespace App\Http\Middleware;

use App\Book;
use App\Scopes\StatusScope;
use Auth;
use Closure;

/**
 * Проверям, существует ли книга
 *
 * Class CheckBookExist
 * @package App\Http\Middleware
 */
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
        $book_id = $request->book_id ?? $request->id;
        $book = Book::findAny($book_id);

        if (blank($book)) {
            return response(view('errors.404'), 404);
        }

        if ($book->status === Book::STATUS_CLOSE && optional(Auth::user())->id !== $book->author_id) {
            return response(view('errors.404'), 404);
        }

        return $next($request);
    }
}
