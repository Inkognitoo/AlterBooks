<?php

namespace App\Http\Middleware\Api;

use App\Models\Book;
use App\Exceptions\ApiException;
use Auth;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверяем, может ли пользователь манипулировать книгой в библиотеке
 *
 * Class CanUserLibraryBook
 * @package App\Http\Middleware\Api
 */
class CanUserLibraryBook
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

        if (Auth::user()->isAuthor($book)) {
            throw new ApiException(t('library.api', 'вы не можете манипулировать в библиотеке своей собственной книгой'), Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return $next($request);
    }
}
