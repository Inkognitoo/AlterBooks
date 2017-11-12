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
 *
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
        $book = Book::findAny($request->id);
        if ($user->id == $book->author_id) {
            return response(view('errors.405'), 405);
        }

        if ($user->hasReview($book)) {
            return response(view('errors.405'), 405);
        }

        return $next($request);
    }
}
