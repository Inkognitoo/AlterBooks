<?php

namespace App\Http\Middleware\Api;

use App\Models\Book;
use Closure;
use Auth;

/**
 * Проверяем, имеет ли пользователь право оставлять рецензию к данной книге
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

        if (is_numeric($book_id)) {
            $book_id = 'id' . $book_id;
        }

        if (Auth::user()->id === Book::findAny($book_id)->author_id) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}
