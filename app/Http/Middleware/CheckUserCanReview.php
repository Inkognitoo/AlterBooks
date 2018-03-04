<?php

namespace App\Http\Middleware;

use Auth;
use App\Book;
use Closure;

/**
 * Проверяем, может ли пользователь оставить рецензию
 *
 * Class CheckUserCanReview
 * @package App\Http\Middleware
 */
class CheckUserCanReview
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
        $user = Auth::user();
        $book_id = $request->book_id ?? $request->id;
        $book = Book::findAny($book_id);

        if ($user->isAuthor($book)) {
            return response(view('errors.405'), 405);
        }

        if ($user->hasBookReview($book)) {
            return response(view('errors.405'), 405);
        }

        return $next($request);
    }
}
