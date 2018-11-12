<?php

namespace App\Http\Middleware;

use App\Models\Book;
use App\Exceptions\ApiException;
use App\Models\Review;
use Auth;
use Closure;
use Illuminate\Http\Response;

/**
 * Проверям, существует ли рецензия к книге авторизованного пользователя
 *
 * Class IsBookExist
 * @package App\Http\Middleware\Api
 */
class IsReviewExist
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
        $review = Auth::user()->reviews()->where('book_id', $request->book_id)->first();

        if(!blank($request->book_id)) {
            $book = Book::find($request->book_id);
            if ($review->book_id !== $book->id) {
                throw new ApiException(t('review.api', 'Рецензии для текущей книги не существует'), Response::HTTP_NOT_FOUND);
            }
        }

        if (blank($review)) {
            throw new ApiException(t('review.api', 'Рецензии не существует'), Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
