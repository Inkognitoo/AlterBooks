<?php

namespace App\Http\Middleware;

use App\Book;
use App\Exceptions\ApiException;
use Auth;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверям, существует ли книга
 *
 * Class IsBookExist
 * @package App\Http\Middleware\Api
 */
class IsBookExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next)
    {
        $book_id = $request->book_id ?? $request->id;
        $book = Book::findAny($book_id);

        if (blank($book)) {
            throw new ApiException(t('book.api', 'Книги не существует'), Response::HTTP_NOT_FOUND);
        }

        if ($book->isClose() && !(bool) optional(Auth::user())->isAuthor($book)) {
            throw new ApiException(t('book.api', 'Книги не существует'), Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
