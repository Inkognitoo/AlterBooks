<?php

namespace App\Http\Middleware\Api;

use App\Models\Book;
use Closure;
use Auth;

/**
 * Проверяем, имеет ли пользователь право редактировать книгу
 *
 * Class CanUserEditBook
 * @package App\Http\Middleware
 */
class CanUserEditBook
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

        if (!Auth::user()->isAuthor(Book::findAny($book_id))) {
            return response(view('errors.405'), 405);
        }

        return $next($request);
    }
}
