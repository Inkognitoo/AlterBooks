<?php

namespace App\Http\Middleware;

use App\Book;
use App\Scopes\StatusScope;
use Closure;
use Auth;

/**
 * Проверяем, имеет ли пользователь право на работу с книгой
 *
 * Class CheckUserBookGranted
 * @package App\Http\Middleware
 */
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
        $book_id = $request->book_id ?? $request->id;

        if (Auth::user()->id !== Book::findAny($book_id)->author_id) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}
