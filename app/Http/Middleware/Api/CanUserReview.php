<?php

namespace App\Http\Middleware\Api;

use App\Models\Book;
use Closure;
use Auth;

/**
 * Проверяем, имеет ли пользователь право на манипуляции с рецензией
 *
 * Class CheckUserBookGranted
 * @package App\Http\Middleware
 */
class CanUserReview
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