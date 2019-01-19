<?php

namespace App\Http\Middleware\Api;

use App\Exceptions\ApiException;
use App\Models\Book;
use Closure;
use Auth;
use Illuminate\Http\Response;

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
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next)
    {
        $book_id = $request->book_id ?? $request->id;

        if (is_numeric($book_id)) {
            $book_id = 'id' . $book_id;
        }

        if (!Auth::user()->isAuthor(Book::findAny($book_id))) {
            throw new ApiException('Вы не можете редактировать чужую книгу', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return $next($request);
    }
}
