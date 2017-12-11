<?php

namespace App\Http\Middleware\Api;

use App\Book;
use Auth;
use Closure;

/**
 * Проверяем, может ли пользователь манипулировать книгой в библиотеке
 *
 * Class CanUserLibraryBook
 * @package App\Http\Middleware\Api
 */
class CanUserLibraryBook
{
    /** @var array $out */
    private $out = [
        'success' => false,
        'code' => 403,
        'data' => [
            'message' => 'You cannot manipulate in library your own book'
        ]
    ];

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
        $book = Book::find($book_id);

        if (Auth::user()->isAuthor($book)) {
            return response()->json($this->out);
        }

        return $next($request);
    }
}
