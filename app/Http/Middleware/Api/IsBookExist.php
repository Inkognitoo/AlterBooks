<?php

namespace App\Http\Middleware;

use App\Book;
use App\Scopes\StatusScope;
use Auth;
use Closure;

/**
 * Проверям, существует ли книга
 *
 * Class IsBookExist
 * @package App\Http\Middleware\Api
 */
class IsBookExist
{
    /** @var array $out */
    private $out = [
        'success' => false,
        'code' => 404,
        'data' => [
            'message' => ''
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
        $this->out['data']['message'] = t('book.api', 'Книги не существует');
        $book_id = $request->book_id ?? $request->id;
        $book = Book::findAny($book_id);

        if (blank($book)) {
            return response()->json($this->out);
        }

        if ($book->isClose() && !(bool) optional(Auth::user())->isAuthor($book)) {
            return response()->json($this->out);
        }

        return $next($request);
    }
}
